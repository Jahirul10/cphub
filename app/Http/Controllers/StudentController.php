<?php

namespace App\Http\Controllers;

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
        if(Auth::check())
        {
            $user = Auth::user();
            if($user->user_type == 2)
            {
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
}