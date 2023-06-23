<?php

namespace App\Http\Controllers;

use App\Models\Problem;
use App\Models\Student;
use App\Models\Submissions;

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

        return view('teacherDashboard', compact('students', 'platformCounts'));
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
}
