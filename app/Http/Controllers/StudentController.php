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
    // public function dashboard()
    // {
    //     $submissionHistory = student::find(191941537);
    //     return view('studentDashboard', $submissionHistory);
    // }



    public function showSubmissionHistory($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 2) {
                // Retrieve the student by their ID
                $student = student::findOrFail($id);

                // Retrieve all submissions by this student and eager load the associated problem
                // $submissions = submissions::with('problem')->where('student_id', $id)->get();

                // get the query param named platform
                $platform = request()->query('platform');

                if ($platform) {
                    $platforms = explode('*', $platform);

                    $submissions = submissions::with('problem')
                        // search for the platforms under problem table and oj field
                        ->where('student_id', $id)
                        ->whereHas('problem', function ($query) use ($platforms) {
                            $query->whereIn('oj', $platforms);
                        })
                        ->orderBy('submissiontime', 'desc')
                        ->paginate(20)->withQueryString();
                    // dd($submissions);

                } else {
                    $submissions = submissions::with('problem')
                        ->where('student_id', $id)
                        ->orderBy('submissiontime', 'desc')
                        ->paginate(20)->withQueryString();
                }
                // dd($submissions);
                // dd($platform);
                // Get the current year
                $currentYear = Carbon::now()->year;

                $submissionsCount = submissions::where('student_id', $id)
                    ->whereYear('submissiontime', $currentYear)
                    ->selectRaw('DATE(submissiontime) as date, COUNT(*) as count')
                    ->groupBy('date')
                    ->get();

                // print_r(json_encode($submissionsCount));


                // Prepare the data for the heatmap graph
                $dailycount = [];
                foreach ($submissionsCount as $item) {
                    $date = $item->date;
                    $count = $item->count;
                    $dailycount[] = [(int)date('Y', strtotime($date)), (int)date('n', strtotime($date)) - 1, (int)date('j', strtotime($date)), $count];
                }
                // dd($dailycount);
                // print_r(json_encode($dailycount));


                $verdictCounts = submissions::where('student_id', $id)
                    ->selectRaw('verdict,COUNT(*) as count')
                    ->groupBy('verdict')
                    ->get();
                // print_r(json_encode(($verdictCounts)));
                $languageCounts = submissions::where('student_id', $id)
                    ->selectRaw('language,COUNT(*) as count')
                    ->groupBy('language')
                    ->get();
                // Pass the student and their submissions to the view with query param platform
                return view('studentDashboard', compact('student', 'submissions', 'dailycount', 'verdictCounts', 'languageCounts'));
            }
        } else {
            return redirect('/login');
        }
    }

    public function showSubmissionHistory2($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 2) {
                // Retrieve the student by their ID
                $student = student::findOrFail($id);

                // Retrieve the platforms and studentId from the request data
                $platforms = ['codeforces', 'vjudge', 'spoj'];
                $studentId = $student->id;

                // // Retrieve all submissions of the student from the submissions table using raw query
                // $submissions = DB::select("SELECT * FROM submissions WHERE student_id = ?", [$studentId]);

                // foreach ($submissions as $submission) {
                //     // Retrieve the problem information for the current submission
                //     $problem = DB::selectOne("SELECT * FROM problems WHERE id = ?", [$submission->problem_id]);

                //     // Add the problem information to the submission
                //     $submission->problem_name = $problem->title;
                //     $submission->problem_url = $problem->url;
                //     $submission->problem_oj = $problem->oj;
                // }

                // //fastest query to get submissions of a student along with problem information
                // $submissions = DB::select("
                //     SELECT s.*, p.title AS problem_title, p.url AS problem_url, p.oj AS problem_oj
                //     FROM submissions s
                //     INNER JOIN problems p ON s.problem_id = p.id
                //     WHERE s.student_id = ?
                // ", [$studentId]);

                //fastest query to get submissions of a student along with problem information
                // Retrieve the submissions of the student from the submissions table
                // Filter by platforms and problem table 'oj' using a JOIN operation
                $submissions = DB::table('submissions')
                ->join('problems', 'submissions.problem_id', '=', 'problems.id')
                ->whereIn('problems.oj', $platforms)
                ->where('submissions.student_id', $studentId)
                    ->select('submissions.*', 'problems.title AS problem_title', 'problems.url AS problem_url', 'problems.oj AS problem_oj')
                    ->get();

                // Calculate the language count from the submissions
                $languagesCount = collect($submissions)->groupBy('language')->map->count();

                // Calculate the verdict count from the submissions
                $verdictsCount = collect($submissions)->groupBy('verdict')->map->count();

                // Get the submission count for dates of the current year
                $currentYear = date('Y');
                $submissionCounts = collect($submissions)
                ->filter(function ($submission) use ($currentYear) {
                    return date('Y', strtotime($submission->submissiontime)) == $currentYear;
                })
                ->groupBy(function ($submission) {
                    return date('Y-m-d', strtotime($submission->submissiontime));
                })
                ->map->count();

                // return view('studentDashboardCopy', compact('student', 'submissions', 'verdictsCount', 'languagesCount'));
                return view('studentDashboardCopy');
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

        // // Retrieve all submissions of the student from the submissions table using raw query
        // $submissions = DB::select("SELECT * FROM submissions WHERE student_id = ?", [$studentId]);

        // foreach ($submissions as $submission) {
        //     // Retrieve the problem information for the current submission
        //     $problem = DB::selectOne("SELECT * FROM problems WHERE id = ?", [$submission->problem_id]);

        //     // Add the problem information to the submission
        //     $submission->problem_name = $problem->title;
        //     $submission->problem_url = $problem->url;
        //     $submission->problem_oj = $problem->oj;
        // }

        // //fastest query to get submissions of a student along with problem information
        // $submissions = DB::select("
        //     SELECT s.*, p.title AS problem_title, p.url AS problem_url, p.oj AS problem_oj
        //     FROM submissions s
        //     INNER JOIN problems p ON s.problem_id = p.id
        //     WHERE s.student_id = ?
        // ", [$studentId]);

        //fastest query to get submissions of a student along with problem information
        // Retrieve the submissions of the student from the submissions table
        // Filter by platforms and problem table 'oj' using a JOIN operation
        $submissions = DB::table('submissions')
            ->join('problems', 'submissions.problem_id', '=', 'problems.id')
            ->whereIn('problems.oj', $platforms)
            ->where('submissions.student_id', $studentId)
            ->select('submissions.*', 'problems.title AS problem_title', 'problems.url AS problem_url', 'problems.oj AS problem_oj')
            ->get();

        // Calculate the language count from the submissions
        $languagesCount = collect($submissions)->groupBy('language')->map->count();

        // Calculate the verdict count from the submissions
        $verdictsCount = collect($submissions)->groupBy('verdict')->map->count();

        // Get the submission count for dates of the current year
        $currentYear = date('Y');
        $submissionCounts = collect($submissions)
            ->filter(function ($submission) use ($currentYear) {
                return date('Y', strtotime($submission->submissiontime)) == $currentYear;
            })
            ->groupBy(function ($submission) {
                return date('Y-m-d', strtotime($submission->submissiontime));
            })
            ->map->count();

        // dd(json_encode($submissions));
        return response()->json([
            'message' => 'Filtering submissions...',
            'submissions' => $submissions,
            'languagesCount' => $languagesCount,
            'verdictsCount' => $verdictsCount,
            'submissionCounts' => $submissionCounts
        ]);
    }
}
