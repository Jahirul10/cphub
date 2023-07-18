<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Student;
use App\Models\Submissions;
use App\Models\RequestedStudent;
use App\Models\User;
use App\Models\handle;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $students = Student::where('session', '2020-21')->get();

        $platforms = ['codeforces', 'vjudge', 'spoj'];
        $platformCounts = [];

        foreach ($students as $student) {
            $submissions = Submissions::where('student_id', $student->id)
            ->whereRaw('LOWER(verdict) = ?', ['accepted'])
            ->select('problem_id')
            ->distinct()
            ->get();

            $problems = $submissions->pluck('problem_id')->toArray();

            $problemPlatforms = Problem::whereIn('id', $problems)
            ->whereIn('oj', $platforms) // Filter by the three specified platforms
            ->pluck('oj')
            ->toArray();

            $platformCounts[$student->id] = array_count_values($problemPlatforms);

            // Fill missing platforms with 0 count
            $platformCounts[$student->id] = array_replace(
                array_fill_keys($platforms, 0),
                $platformCounts[$student->id]
            );
        }

        $topSolving = Submissions::where('verdict', 'accepted')
            ->select('students.id', 'students.name', DB::raw('COUNT(DISTINCT problem_id) as solved'))
            ->groupBy('students.id', 'students.name')
            ->orderByRaw('solved DESC')
            ->limit(10)
            ->join('students', 'submissions.student_id', '=', 'students.id')
            ->get();

        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 1) {
                return view('teacherDashboard', compact('students', 'platformCounts', 'topSolving'));
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

        $platforms = ['codeforces', 'vjudge', 'spoj'];
        $platformCounts = [];

        foreach ($students as $student) {
            $submissions = Submissions::where('student_id', $student->id)
                ->whereRaw('LOWER(verdict) = ?', ['accepted'])
                ->select('problem_id')
                ->distinct()
                ->get();

            $problems = $submissions->pluck('problem_id')->toArray();

            $problemPlatforms = Problem::whereIn('id', $problems)
                ->whereIn('oj', $platforms) // Filter by the three specified platforms
                ->pluck('oj')
                ->toArray();

            $platformCounts[$student->id] = array_count_values($problemPlatforms);

            // Fill missing platforms with 0 count
            $platformCounts[$student->id] = array_replace(
                array_fill_keys($platforms, 0),
                $platformCounts[$student->id]
            );
        }

        $responseData = [
            'students' => $students,
            'platformCounts' => $platformCounts
        ];

        return response()->json($responseData);
    }

    public function studentSubmissionHistory($id)
    {
        // return response()->json($studentSubmissionHistoryData);
        // Retrieve the platforms and studentId from the request data
        $platforms = ["codeforces", "vjudge", "spoj"];
        $studentId = $id;

        //fastest query to get submissions of a student along with problem information
        // Retrieve the submissions of the student from the submissions table
        // Filter by platforms and problem table 'oj' using a JOIN operation
        $submissions = DB::table('submissions')
            ->join('problems', 'submissions.problem_id', '=', 'problems.id')
            ->whereIn('problems.oj', $platforms)
            ->where('submissions.student_id', $studentId)
            ->select('submissions.*', 'problems.title AS problem_title', 'problems.url AS problem_url', 'problems.oj AS problem_oj')
            ->orderBy('submissions.submissiontime', 'desc') // Sort by submissiontime in descending order
            ->get();

        // Calculate the language count from the submissions
        $languagesCount = collect($submissions)->groupBy('language')->map->count();

        // Calculate the verdict count from the submissions
        $verdictsCount = collect($submissions)->groupBy('verdict')->map->count();

        // Calculate the start and end dates for the last 7 days
        $startOfLast7Days = Carbon::now()->subDays(7)->startOfDay();
        $endOfLast7Days = Carbon::now()->endOfDay();

        // Retrieve the submissions of the student from the submissions table
        // Filter by platforms, problem table 'oj', and within the last week using a JOIN operation
        $lastWeekSubmissions = DB::table('submissions')
            ->join('problems', 'submissions.problem_id', '=', 'problems.id')
            ->whereIn('problems.oj', $platforms)
            ->where('submissions.student_id', $studentId)
            ->whereBetween('submissions.submissiontime', [$startOfLast7Days, $endOfLast7Days])
            ->select('submissions.*', 'problems.title AS problem_title', 'problems.url AS problem_url', 'problems.oj AS problem_oj')
            ->orderBy('submissions.submissiontime', 'desc') // Sort by submissiontime in descending order
            ->get();

        $last10Submissions = DB::table('submissions')
            ->join('problems', 'submissions.problem_id', '=', 'problems.id')
            ->whereIn('problems.oj', $platforms)
            ->where('submissions.student_id', $studentId)
            ->select('submissions.*', 'problems.title AS problem_title', 'problems.url AS problem_url', 'problems.oj AS problem_oj')
            ->orderBy('submissions.submissiontime', 'desc') // Sort by submissiontime in descending order
            ->limit(10) // Limit the results to 10 submissions
            ->get();

        return response()->json([
            'last10Submissions' => $last10Submissions,
            'languagesCount' => $languagesCount,
            'verdictsCount' => $verdictsCount,
        ]);
    }

    public function addStudent()
    {
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
