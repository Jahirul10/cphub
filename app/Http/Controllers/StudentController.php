<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\student;
use App\Models\submissions;
use Illuminate\Support\Facades\Auth;
use App\Models\problem;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class StudentController extends Controller
{
    public function showSubmissionHistory($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 1 || $user->user_type == 2) {
                // Retrieve the student by their ID
                $student = student::findOrFail($id);

                $studentId = $student->id;

                $submissions = DB::table('submissions')
                    ->where('student_id', $studentId)
                    ->get();

                // dd($studentId);
                // Get the submission count for dates of the current year
                $currentYear = date('Y');
                $submissionsCount = collect($submissions)
                    ->filter(function ($submission) use ($currentYear) {
                        return date('Y', strtotime($submission->submissiontime)) == $currentYear;
                    })
                    ->groupBy(function ($submission) {
                        return date('Y-m-d', strtotime($submission->submissiontime));
                    })
                    ->map(function ($groupedSubmissions, $date) {
                        $dateComponents = explode('-', $date);
                        return [
                            (int)$dateComponents[0], // Set year as integer
                            (int)$dateComponents[1] - 1, // Set month as integer
                            (int)$dateComponents[2], // Set day as integer
                            $groupedSubmissions->count(),
                        ];
                    })
                    ->values();

                $topSolving = Submissions::where('verdict', 'accepted')
                    ->select('students.id', 'students.name', DB::raw('COUNT(DISTINCT problem_id) as solved'))
                    ->groupBy('students.id', 'students.name')
                    ->orderByRaw('solved DESC')
                    ->limit(10)
                    ->join('students', 'submissions.student_id', '=', 'students.id')
                    ->get();

                return view('studentDashboard', compact('student', 'submissionsCount', 'topSolving'));
            }
        } else {
            return redirect('/login');
        }
    }

    public function filterSubmissions(Request $request)
    {
        // Retrieve the platforms and studentId from the request data
        $platforms = $request->input('platforms');
        $studentId = $request->input('studentId');

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

        return response()->json([
            'message' => 'Filtering submissions...',
            'submissions' => $submissions,
            'languagesCount' => $languagesCount,
            'verdictsCount' => $verdictsCount,
        ]);
    }
}
