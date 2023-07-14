<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Student;
use App\Models\Submissions;
use App\Models\RequestedStudent;
use App\Models\User;
use App\Models\handle;
use Illuminate\Support\Facades\Auth;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $students = Student::where('session', '2020-21')->get();

        // $platforms = ['codeforces', 'vjudge', 'spoj'];
        $platformCounts = [];
        foreach ($students as $student) {
            // print_r(json_encode($student));

            $submissions = Submissions::where('student_id', $student->id)
                ->whereRaw('LOWER(verdict) = ?', ['accepted'])
                ->select('problem_id')
                ->distinct()
                ->get();

            $problems = $submissions->pluck('problem_id')->toArray();

            $problemPlatforms = Problem::whereIn('id', $problems)
                ->pluck('oj')
                ->toArray();
            $platformCounts[$student->id] = array_count_values($problemPlatforms);
            // print_r(json_encode(count($problemPlatforms)));
            // print_r(json_encode($platformCounts));
        }

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 1) {
                return view('teacherDashboard', compact('students', 'platformCounts'));
            } else if ($user->user_type == 3) {
                return redirect('/dashboard');
            } else if ($user->user_type == 4) {
                return redirect('/dashboard');
            }
        } else {
            return redirect('/login');
        }


    }

    public function updateTable($session)
    {
        $students = Student::where('session', $session)->get();
        // print_r($session);

        $platformCounts = [];
        // print_r(json_encode($submissionIds));
        foreach ($students as $student) {
            // print_r("lasjkdflkjsalkdfjlk");

            $submissions = Submissions::where('student_id', $student->id)
                ->whereRaw('LOWER(verdict) = ?', ['accepted'])
                ->select('problem_id')
                ->distinct()
                ->get();

            $problems = $submissions->pluck('problem_id')->toArray();

            $problemPlatforms = Problem::whereIn('id', $problems)
                ->pluck('oj')
                ->toArray();
            $platformCounts[$student->id] = array_count_values($problemPlatforms);
            // print_r(json_encode(count($problemPlatforms)));
            // print_r(json_encode($platformCounts));
        }

        $responseData = [
            'students' => $students,
            'platformCounts' => $platformCounts
        ];

        return response()->json($responseData);
    }

    public function addStudent(){
        $userEmail = Auth::user()->email;
        $requestedStudents = RequestedStudent::where('receiver', $userEmail)->get();
        return view('addStudent', compact('requestedStudents'));
    }

    public function deleteRequest($studentId)
    {
        // Find the student request and retrieve the associated user ID
        $studentRequest = RequestedStudent::find($studentId);
        if (!$studentRequest) {
            return response()->json(['success' => false, 'message' => 'Student request not found'], 404);
        }

        $userId = $studentRequest->user_id;

        // Delete the student request from the database
        $studentRequest->delete();

        // Update the user_type in the user table
        $user = User::find($userId);
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'User not found'], 404);
        }

        // Set the user_type to 4 for a student
        $user->user_type = 4;
        $user->save();

        return response()->json(['success' => true, 'message' => 'Student request deleted']);
    }

    public function acceptRequest($studentId)
    {
        // dd('acceptRequest method called');
        // Retrieve the student request
        $studentRequest = RequestedStudent::find($studentId);
        // dd($studentId);

        if (!$studentRequest) {
            return response()->json(['success' => false, 'message' => 'Student request not found'], 404);
        }

        // Insert details into the students table
        $student = new Student();
        $student->id = $studentRequest->id;
        $student->name = $studentRequest->name;
        $student->phone = $studentRequest->phone;
        $student->session = $studentRequest->session;
        $student->user_id = $studentRequest->user_id;
        $student->save();

        // Insert details into the handles table
        $handle = new Handle();
        $handle->id = $studentRequest->id;
        $handle->cfhandle = $studentRequest->cfhandle;
        $handle->vjhandle = $studentRequest->vjhandle;
        $handle->spojhandle = $studentRequest->spojhandle;
        $handle->cf_last_submission = 0;
        $handle->vj_last_submission = 0;
        $handle->spoj_last_submission = 0;
        $handle->save();

        // Update the user_type in the users table
        $user = User::find($studentRequest->user_id);
        if ($user) {
            $user->user_type = 2; // Update to user_type 2 (assuming this corresponds to the accepted student role)
            $user->save();
        }

        // Delete the student request
        $studentRequest->delete();

        return response()->json(['success' => true, 'message' => 'Student request accepted']);
    }
}
