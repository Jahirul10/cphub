<?php

namespace App\Http\Controllers;

use App\Models\student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class VisitorController extends Controller
{
    public function signup(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,NULL,id,password,' . $request->password,
                'password' => 'required|min:1',
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Invalid email format.',
                'email.unique' => 'The email address is already taken.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 1 characters.',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'user_type' => 4, // Assuming 4 represents a regular user
            ]);

            // Perform any additional actions or redirects here
            if (Auth::attempt(['email' => $validatedData['email'], 'password' => $validatedData['password']])) {
                $user = Auth::user();

                // Generate and save API token if needed
                if ($user) {
                    $apiToken = Str::random(80);
                    User::where('id', $user->id)->update(['api_token' => $apiToken]);
                }

                if ($user->user_type == 3) {
                    return redirect('/teacher-dashboard')->with('success', 'Login successful!');
                } else {
                    return redirect('/dashboard')->with('success', 'Login successful!');
                }
            } else {
                return redirect('/login')->withErrors([
                    'email' => 'Sign up failed. Please log in.',
                ]);
            }
        } catch (\Illuminate\Database\QueryException $exception) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode === 1062) { // Duplicate entry error code
                return back()->withInput()->withErrors(['email' => 'The email address is already taken.']);
            }

            // Handle other database errors if needed
            return back()->withInput()->withErrors(['unexpected_error' => 'An unexpected error occurred.']);
        }
    }

    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            // print_r($user->user_type);
            if ($user->user_type == 3 || $user->user_type == 4) {
                return view('dashboard');
            } else if ($user->user_type == 1) {
                return redirect('/teacher-dashboard');
            } else if ($user->user_type == 2) {
                $student = Student::where('user_id', $user->id)->first();
                if ($student) {
                    $studentId = $student->id;
                    return redirect("/student/{$studentId}");
                } else {
                    // Handle the case where the student record is not found
                    // You can redirect to an appropriate error page or handle it as per your requirements
                }
            }
        } else {
            return redirect('/home');
        }
    }

    public function login()
    {
        return view('login');
    }

    public function home()
    {
        return view('publicSearch');
    }
    public function searchResult()
    {
        return view('publicSearchResult');
    }
    public function showComparisonForm(){
        return view('publicComparison');
    }
    public function searchingData(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'codeforces' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'vjudge' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'spoj' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
        ], [
            'codeforces.regex' => 'The codeforces handle contains invalid characters.',
            'vjudge.regex' => 'The vjudge handle contains invalid characters.',
            'spoj.regex' => 'The spoj handle contains invalid characters.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'error' => 'Validation failed',
                'errors' => array_values($errors),
            ], 400);
        }

        $codeforcesHandle = $request->input('codeforces');
        $vjudgeHandle = $request->input('vjudge');
        $spojHandle = $request->input('spoj');

        //getting data from Codeforces server

        $path = base_path('app/scraping/publicCodeforces.php');
        exec("php \"$path\" \"$codeforcesHandle\"", $output);
        $jsonResponseCodeforces = end($output);
        $dataArrayOfCodeforces = json_decode($jsonResponseCodeforces, true);

        // getting all data from Vjude

        $path = base_path('app/scraping/publicVjudge.php');
        exec("php \"$path\" \"$vjudgeHandle\"", $dataVjudge);
        $jsonResponseVjudge = end($dataVjudge);
        $dataArrayOfVjudge = json_decode($jsonResponseVjudge, true);

        //get all data from  spoj data

        $path = base_path('app/scraping/publicSpoj.php');
        exec("php \"$path\" \"$spojHandle\"", $dataSpoj);
        $jsonResponseSpoj = end($dataSpoj);
        $dataArrayOfSpoj = json_decode($jsonResponseSpoj, true);

        $mergedData = [];
        $message = array();

        if (!empty($dataArrayOfCodeforces)) {
            $mergedData = array_merge($mergedData, $dataArrayOfCodeforces);
        }
        else $message[] = "No codeforces data found.";


        if (!empty($dataArrayOfVjudge)) {
            foreach ($dataArrayOfVjudge as &$item) {
                if ($item[2] === 'CodeForces') {
                    $item[2] = 'codeforces';
                } elseif ($item[2] === 'SPOJ') {
                    $item[2] = 'spoj';
                } else {
                    $item[2] = 'vjudge';
                }
            }
            $mergedData = array_merge($mergedData, $dataArrayOfVjudge);
        } else $message[] ="No vjudge data found.";

        if (!empty($dataArrayOfSpoj)) {
            $mergedData = array_merge($mergedData, $dataArrayOfSpoj);
        } else $message[] = "No spoj data found.";

        $verdictsCount = array();
        $languagesCount = array();

        foreach ($mergedData as $data) {
            $verdict = $data[3]; // Index 3 represents the verdict
            $language = $data[4]; // Index 4 represents the language

            if (isset($verdictsCount[$verdict])) {
                $verdictsCount[$verdict]++;
            } else {
                $verdictsCount[$verdict] = 1;
            }

            if (isset($languagesCount[$language])) {
                $languagesCount[$language]++;
            } else {
                $languagesCount[$language] = 1;
            }
        }

        return response()->json([
            'message' => $message,
            'submissions' => $mergedData,
            'verdictsCount' => $verdictsCount,
            'languagesCount' => $languagesCount
        ]);
    }














    public function showComparison(Request $request){
        $validator = Validator::make($request->all(), [
            'user_1_codeforcesHandle' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'user_1_vjudgeHandle' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'user_1_spojHandle' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'user_2_codeforcesHandle' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'user_2_vjudgeHandle' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
            'user_2_spojHandle' => ['nullable', 'string', 'max:20', 'regex:/^[A-Za-z0-9_]+$/'],
        ], [
            'user_1_codeforcesHandle.regex' => 'The user 1 codeforces handle contains invalid characters.',
            'user_1_vjudgeHandle.regex' => 'The user 1 vjudge handle contains invalid characters.',
            'user_1_spojHandle.regex' => 'The user 1 spoj handle contains invalid characters.',
            'user_2_codeforcesHandle.regex' => 'The user 2 codeforces handle contains invalid characters.',
            'user_2_vjudgeHandle.regex' => 'The user 2 vjudge handle contains invalid characters.',
            'user_2_spojHandle.regex' => 'The user 2 spoj handle contains invalid characters.',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->all();

            return response()->json([
                'error' => 'Validation failed',
                'errors' => array_values($errors),
            ], 400);
        }



        $user_1_codeforcesHandle = $request->input('user_1_codeforcesHandle');
        $user_1_vjudgeHandle = $request->input('user_1_vjudgeHandle');
        $user_1_spojHandle = $request->input('user_1_spojHandle');
        $user_2_codeforcesHandle = $request->input('user_2_codeforcesHandle');
        $user_2_vjudgeHandle = $request->input('user_2_vjudgeHandle');
        $user_2_spojHandle = $request->input('user_2_spojHandle');

        //getting data from Codeforces server for User-1
        // return $user_1_codeforcesHandle;
        $path = base_path('app/scraping/publicCodeforces.php');
        exec("php \"$path\" \"$user_1_codeforcesHandle\"", $output);
        $jsonResponseCodeforces = end($output);
        $dataArrayOfCodeforces_User_1 = json_decode($jsonResponseCodeforces, true);

        //getting data from Codeforces server for user-2

        $path = base_path('app/scraping/publicCodeforces.php');
        exec("php \"$path\" \"$user_2_codeforcesHandle\"", $output);
        $jsonResponseCodeforces = end($output);
        $dataArrayOfCodeforces_User_2 = json_decode($jsonResponseCodeforces, true);

        // getting all data from Vjude for user-1

        $path = base_path('app/scraping/publicVjudge.php');
        exec("php \"$path\" \"$user_1_vjudgeHandle\"", $dataVjudge);
        $jsonResponseVjudge = end($dataVjudge);
        $dataArrayOfVjudge_User_1 = json_decode($jsonResponseVjudge, true);

        // getting all data from Vjude for user-2

        $path = base_path('app/scraping/publicVjudge.php');
        exec("php \"$path\" \"$user_2_vjudgeHandle\"", $dataVjudge);
        $jsonResponseVjudge = end($dataVjudge);
        $dataArrayOfVjudge_User_2 = json_decode($jsonResponseVjudge, true);

        //get all data from  spoj for user-1

        $path = base_path('app/scraping/publicSpoj.php');
        exec("php \"$path\" \"$user_1_spojHandle\"", $dataSpoj);
        $jsonResponseSpoj = end($dataSpoj);
        $dataArrayOfSpoj_User_1 = json_decode($jsonResponseSpoj, true);

        //get all data from  spoj for user-2

        $path = base_path('app/scraping/publicSpoj.php');
        exec("php \"$path\" \"$user_2_spojHandle\"", $dataSpoj);
        $jsonResponseSpoj = end($dataSpoj);
        $dataArrayOfSpoj_User_2 = json_decode($jsonResponseSpoj, true);


        //merging all data of user-1
        $mergedData_User_1 = [];

        if (!empty($dataArrayOfCodeforces_User_1)) {
            $mergedData_User_1 = array_merge($mergedData_User_1, $dataArrayOfCodeforces_User_1);
        }

        // return $dataArrayOfVjudge_User_1;
        if (!empty($dataArrayOfVjudge_User_1)) {

            foreach ($dataArrayOfVjudge_User_1 as &$item) {
                if ($item[2] === 'CodeForces') {
                    $item[2] = 'codeforces';
                }elseif ($item[2] === 'SPOJ') {
                    $item[2] = 'spoj';
                } else{
                    $item[2] = 'vjudge';
                }
            }

            $mergedData_User_1 = array_merge($mergedData_User_1, $dataArrayOfVjudge_User_1);
        }

        if (!empty($dataArrayOfSpoj_User_1)) {
            $mergedData_User_1 = array_merge($mergedData_User_1, $dataArrayOfSpoj_User_1);
        }

        //merging all data of user-2

        $mergedData_User_2 = [];

        if (!empty($dataArrayOfCodeforces_User_2)) {
            $mergedData_User_2 = array_merge($mergedData_User_2, $dataArrayOfCodeforces_User_2);
        }
        if (!empty($dataArrayOfVjudge_User_2)) {

            foreach ($dataArrayOfVjudge_User_2 as &$item) {
                if ($item[2] === 'CodeForces') {
                    $item[2] = 'codeforces';
                }elseif ($item[2] === 'SPOJ') {
                    $item[2] = 'spoj';
                } else{
                    $item[2] = 'vjudge';
                }
            }


            $mergedData_User_2 = array_merge($mergedData_User_2, $dataArrayOfVjudge_User_2);
        }


        if (!empty($dataArrayOfSpoj_User_2)) {
            $mergedData_User_2 = array_merge($mergedData_User_2, $dataArrayOfSpoj_User_2);
        }
        $codeforcesTotalProblems_User_1=[];
        $vjudgeTotalProblems_User_1 = [];
        $spojTotalProblems_User_1 = [];

        // return $mergedData_User_1;
        foreach ($mergedData_User_1 as $item) {
            if ($item[2] === 'codeforces' && $item[3] === 'Accepted') {
                $name = $item[1];
                $codeforcesTotalProblems_User_1[] = $name;
            }
            elseif ($item[2] === 'vjudge' && $item[3] === 'Accepted') {
                $name = $item[1];
                $vjudgeTotalProblems_User_1[] = $name;
            }
            elseif ($item[2] === 'spoj' && $item[3] === 'Accepted') {
                $name = $item[1];
                $spojTotalProblems_User_1[] = $name;
            }
        }
        // return $codeforcesTotalProblems_User_1;
        // return $spojTotalProblems_User_1;
        $uniqueCodeforceProblems_User_1 = array_unique($codeforcesTotalProblems_User_1);
        $totalCodeforcesSolved_User_1 = count($uniqueCodeforceProblems_User_1);

        $uniqueVjudgeProblems_User_1 = array_unique($vjudgeTotalProblems_User_1);
        $totalVjudgeSolved_User_1 = count($uniqueVjudgeProblems_User_1);

        $uniqueSpojProblems_User_1 = array_unique($spojTotalProblems_User_1);
        $totalSpojSolved_User_1 = count($uniqueSpojProblems_User_1);

        //counting for user 2


        $codeforcesTotalProblems_User_2=[];
        $vjudgeTotalProblems_User_2 = [];
        $spojTotalProblems_User_2 = [];

        foreach ($mergedData_User_2 as $item) {
            if ($item[2] === 'codeforces' && $item[3] === 'Accepted') {
                $name = $item[1];
                $codeforcesTotalProblems_User_2[] = $name;
            }
            elseif ($item[2] === 'vjudge' && $item[3] === 'Accepted') {
                $name = $item[1];
                $vjudgeTotalProblems_User_2[] = $name;
            }
            elseif ($item[2] === 'spoj' && $item[3] === 'Accepted') {
                $name = $item[1];
                $spojTotalProblems_User_2[] = $name;
            }
        }
        // return $spojTotalProblems_User_1;
        $uniqueCodeforceProblems_User_2 = array_unique($codeforcesTotalProblems_User_2);
        $totalCodeforcesSolved_User_2 = count($uniqueCodeforceProblems_User_2);

        $uniqueVjudgeProblems_User_2 = array_unique($vjudgeTotalProblems_User_2);
        $totalVjudgeSolved_User_2 = count($uniqueVjudgeProblems_User_2);

        $uniqueSpojProblems_User_2 = array_unique($spojTotalProblems_User_2);
        $totalSpojSolved_User_2 = count($uniqueSpojProblems_User_2);

        // return $totalSpojSolved_User_2;
        // return $totalCodeforcesSolved_User_2;
        // return $totalVjudgeSolved_User_2;

        //language data retrieving functions
        $language_User_1 = array();
        foreach ($mergedData_User_1 as $lanitem) {
            if (isset($language_User_1[$lanitem[4]])) {
                $language_User_1[$lanitem[4]]++;
            } else {
                $language_User_1[$lanitem[4]] = 1;
            }
        }
        $language_User_2 = array();
        foreach ($mergedData_User_2 as $lanitem) {
            if (isset($language_User_2[$lanitem[4]])) {
                $language_User_2[$lanitem[4]]++;
            } else {
                $language_User_2[$lanitem[4]] = 1;
            }
        }

        //veerdict count

        $verdict_User_1 = array();
        foreach ($mergedData_User_1 as $lanitem) {
            if (isset($verdict_User_1[$lanitem[3]])) {
                $verdict_User_1[$lanitem[3]]++;
            } else {
                $verdict_User_1[$lanitem[3]] = 1;
            }
        }

        $verdict_User_2 = array();
        foreach ($mergedData_User_2 as $lanitem) {
            if (isset($verdict_User_2[$lanitem[3]])) {
                $verdict_User_2[$lanitem[3]]++;
            } else {
                $verdict_User_2[$lanitem[3]] = 1;
            }
        }

        // print_r($language_User_1);
        // print_r($language_User_2);

        return view('comparisonResult',compact('totalCodeforcesSolved_User_1','totalVjudgeSolved_User_1','totalSpojSolved_User_1','totalCodeforcesSolved_User_2','totalVjudgeSolved_User_2','totalSpojSolved_User_2','language_User_1', 'language_User_2','verdict_User_1','verdict_User_2'));




    }

    public function showJoinRequestForm()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 1) {
                return redirect('/teacher-dashboard');
            } else if ($user->user_type == 3) {
                return view('successfulRequest');
            } else if ($user->user_type == 4) {
                return view('joinRequest');
            }
        } else {
            return redirect('/login');
        }
    }

    public function joinRequest(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'studentId' => 'required',
            'phone' => 'required',
            'session' => 'required',
            'codeforcesHandle' => 'required',
            'vjudgeHandle' => 'required',
            'spojHandle' => 'required',
            'email' => 'required|email',
        ]);

        $studentId = $validatedData['studentId'];

        // Check if the student ID already exists in the students table
        $studentExists = DB::table('students')->where('id', $studentId)->exists();

        if ($studentExists) {
            // Student ID already exists in the students table
            // Handle the error or return a response indicating the conflict
            return response()->json(['error' => 'student_exists']);
        }

        // Check if the student ID already exists in the requested_students table
        $requestedStudentExists = DB::table('requested_students')->where('id', $studentId)->exists();

        if ($requestedStudentExists) {
            // Student ID already exists in the requested_students table
            // Handle the error or return a response indicating the conflict
            return response()->json(['error' => 'join_request_exists']);
        }

        // Get the logged-in user's name and ID
        $user = Auth::user();
        $name = $user->name;
        $userId = $user->id;

        // Insert the data into the requested_students table using a query
        DB::table('requested_students')->insert([
            'id' => $studentId,
            'phone' => $validatedData['phone'],
            'name' => $name,
            'session' => $validatedData['session'],
            'cfhandle' => $validatedData['codeforcesHandle'],
            'vjhandle' => $validatedData['vjudgeHandle'],
            'spojhandle' => $validatedData['spojHandle'],
            'created_at' => now(),
            'updated_at' => now(),
            'user_id' => $userId,
            'receiver' => $validatedData['email'],
        ]);

        // Set the user type to 3 (assuming user type 3 represents joined students)
        User::where('id', $userId)->update(['user_type' => 3]);

        return response()->json(['success' => true]);
    }

    public function successfulRequest()
    {
        // if (Auth::check()) {
        //     $user = Auth::user();
        //     if ($user->user_type == 1) {
        //         return redirect('/teacher-dashboard');
        //     } else if ($user->user_type == 2) {
        //         return view('successfulRequest');
        //     } else if ($user->user_type == 3) {
        //         return view('successfulRequest');
        //     } else if ($user->user_type == 4) {
        //         return redirect('/join-request');
        //     }
        // } else {
        //     return redirect('/login');
        // }

        if (Auth::check()) {
            $user = Auth::user();
            $message = ''; // Set a default value for the message

            if ($user->user_type == 1) {
                return redirect('/teacher-dashboard');
            } else if ($user->user_type == 2) {
                $message = 'Your joining request has been accepted.';
            } else if ($user->user_type == 3) {
                $message = 'Joining request sent successfully!';
            } else if ($user->user_type == 4) {
                return view('joinRequest');
            }

            return view('successfulRequest', compact('message'));
        } else {
            return redirect('/login');
        }
    }
}
