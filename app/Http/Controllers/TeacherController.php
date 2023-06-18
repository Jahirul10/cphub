<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Student;
use App\Models\Submissions;
use Illuminate\Support\Facades\DB;

class TeacherController extends Controller
{
    public function dashboard()
    {
        $students = Student::where('session', '2019-20')->get();

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
            $uniqueProblems = array();
            foreach ($submissions as $submissionId) {

                $submission = Submissions::where('submission_id', $submissionId)->get();
                // print_r($submissionId);
                // print_r($submission);
                // echo "\n";

                $problemId = Submissions::where('submission_id', $submissionId)->value('problem_id');
                // echo "\n";
                // print_r($studentId);


                $platform = Problem::where('id', $problemId)->value('oj');
                // echo "\n";

                $verdict = $submission->pluck('verdict')->first();
                if (strcasecmp($verdict, 'ACCEPTED') == 0 && !in_array($problemId, $uniqueProblems)) {
                    // print_r($platformCounts['1810876124']['spoj']);
                    // print_r($verdict);
                    $uniqueProblems[] = $problemId;

                    if (!isset($platformCounts[$studentId][$platform])) {
                        $platformCounts[$studentId][$platform] = 0;
                    }

                    $platformCounts[$studentId][$platform]++;
                }
            }
        }

        return view('teacherDashboard', compact('students', 'submissionIds', 'platformCounts'));

    }
}
