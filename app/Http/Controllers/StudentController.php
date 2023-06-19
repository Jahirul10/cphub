<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student;
use App\Models\submissions;
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
        // Retrieve the student by their ID
        $student = student::findOrFail($id);

        // Retrieve all submissions by this student and eager load the associated problem
        // $submissions = submissions::with('problem')->where('student_id', $id)->get();

        // get the query param named platform
        $platform = request()->query('platform');

        if($platform){
            $platforms = explode('*', $platform);

            $submissions = submissions::with('problem')
            // search for the platforms under problem table and oj field
            ->where('student_id', $id)
            ->whereHas('problem', function($query) use ($platforms){
                $query->whereIn('oj', $platforms);
            })
            ->orderBy('submissiontime', 'desc')
            ->paginate(20)->withQueryString();
            // dd($submissions);

        }
        else{
            $submissions = submissions::with('problem')
            ->where('student_id', $id)
            ->orderBy('submissiontime', 'desc')
            ->paginate(20)->withQueryString();

        }
        // dd($platform);
        // Get the current year
        $currentYear = Carbon::now()->year;

        //heatmap  value counting
        // $submissionsCount = submissions::selectRaw('DATE(submissiontime) as date, COUNT(*) as count')
        //     ->where('student_id', $id)
        //     ->whereYear('submissiontime', $currentYear)
        //     ->groupBy('date')
        //     ->get();
        $submissionsCount = submissions::where('student_id', $id)
            ->whereYear('submissiontime', $currentYear)
            ->groupBy('date')
            ->selectRaw('DATE(submissiontime) as date, COUNT(*) as count')
            ->get();


    // Prepare the data for the heatmap graph
        $dailycount = [];
        foreach ($submissionsCount as $item) {
            $date = $item->date;
            $count = $item->count;
            $dailycount[] = [(int)date('Y', strtotime($date)), (int)date('n', strtotime($date)) - 1, (int)date('j', strtotime($date)), $count];
        }
        // dd($dailycount);

        // Pass the student and their submissions to the view with query param platform
        return view('studentDashboard', compact('student', 'submissions', 'dailycount'));
    }
}