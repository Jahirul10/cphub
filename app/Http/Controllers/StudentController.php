<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\student;
use App\Models\submissions;
use App\Models\problem;

class StudentController extends Controller
{
    // public function dashboard()
    // {
    //     $submissionHistory = student::find(191941537);
    //     return view('studentDashboard', $submissionHistory);
    // }



    public function showSubmissionHistory($id)
    {
        $student = student::findOrFail($id); // Retrieve the student by their ID
        $submissions = submissions::with('problem')->where('student_id', $id)->get(); // Retrieve all submissions by this student and eager load the associated problem
        return view('studentDashboard', compact('student', 'submissions')); // Pass the student and their submissions to the view
    }
}
