<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Submissions;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $students = Student::where('session', '2017-18')->get();
        // $submissionIds = Submissions::whereHas('student', function ($query) use ($students) {
        //     $query->whereIn('id', $students->pluck('id'));
        // })->where('verdict', 'accepted')->pluck('id');

        $submissionIds = Submissions::whereIn('student_id', $students->pluck('id'))
            ->where('verdict', 'accepted')
            ->select('student_id', 'id')
            ->get()
            ->groupBy('student_id')
            ->map(function ($submissions) {
                return $submissions->pluck('id')->toArray();
            })->toArray();


        $platformCounts = [];
        foreach ($submissionIds as $studentId => $submissions) {
            foreach ($submissions as $submissionId) {
                $submission = Submissions::find($submissionId);
                $platform = $submission->problem->oj;

                if (!isset($platformCounts[$studentId][$platform])) {
                    $platformCounts[$studentId][$platform] = 0;
                }

                if ($submission->verdict === 'accepted') {
                    $platformCounts[$studentId][$platform]++;
                }
            }
        }

        return view('teacherDashboard', compact('students', 'submissionIds', 'platformCounts'));

    }
}
