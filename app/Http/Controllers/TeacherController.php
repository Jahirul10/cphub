<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\Submissions;
use App\Models\Problem;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $students = Student::where('session', '2017-18')->get();

        $submissionIds = Submissions::whereIn('student_id', $students->pluck('id'))
            ->where('verdict', 'accepted')
            ->select('student_id', 'submission_id')
            ->get()
            ->groupBy('student_id')
            ->map(function ($submissions) {
                return $submissions->pluck('submission_id')->toArray();
            })->toArray();


        $platformCounts = [];
        $platforms = ['codeforces', 'vjudge', 'spoj'];

        foreach ($students as $student) {
            $platformCounts[$student->id] = array_fill_keys($platforms, 0);
        }

        foreach ($submissionIds as $studentId => $submissions) {
            foreach ($submissions as $submissionId) {
                
                $submission = Submissions::where('submission_id', $submissionId)->get();

                $platform = Problem::where('id', $submission->pluck('problem_id'))->first()->oj;

                if ($submission->pluck('verdict')->first() === 'ACCEPTED') {
                    $platformCounts[$studentId][$platform]++;
                }
            }
        }

        return view('teacherDashboard', compact('students', 'submissionIds', 'platformCounts'));

    }
}
