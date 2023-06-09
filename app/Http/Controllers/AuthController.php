<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Student;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            // Authentication passed
            $user = Auth::user();

            // Generate and save API token if needed
            if ($user) {
                $apiToken = Str::random(80);
                User::where('id', $user->id)->update(['api_token' => $apiToken]);
            }

            if ($user->user_type == 3) {
                return redirect('/teacher-dashboard')->with('success', 'Login successful!');
            } else if ($user->user_type == 2) {

                $student = Student::where('user_id', $user->id)->first();

                // Retrieve the student record
                if ($student) {
                    $id = $student->id; // Get the ID of the student
                    return redirect()->route('student.show', ['id' => $id]);
                } else {
                    // Handle the case when no student record is found
                    return redirect('/')->with('error', 'Student record not found!');
                }
            } else {
                return redirect('/dashboard')->with('success', 'Login successful!');
            }
        } else {
            // Authentication failed
            return redirect()->back()->withErrors([
                'email' => 'Invalid credentials.',
            ]);
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'Logged out successfully!');
    }
}
