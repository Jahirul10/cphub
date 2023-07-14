<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\student;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

    public function searchingData(Request $request)
    {
        $codeforcesHandle = $request->input('codeforces');
        $vjudgeHandle = $request->input('vjudge');
        $spojHandle = $request->input('spoj');

        // print_r($codeforcesHandle);
        // print_r($vjudgeHandle);
        // print_r($spojHandle);

        // Process the received data or perform any necessary operations

        $path = base_path('app/scraping/publicCodeforces.php');

        exec("php \"$path\" \"$codeforcesHandle\"", $output);
        $jsonResponse = end($output);
        $data = json_decode($jsonResponse, true);
        // print_r($data);
        $paginatedData = collect($data)->paginate(10);
        return view('searchResultDashboard', compact('paginatedData'));
        // return view('searchResultDashboard', compact('data'));
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
