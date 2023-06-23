<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class VisitorController extends Controller
{
    public function signup(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required',
                'email' => 'required|email|unique:users,email,NULL,id,password,' . $request->password,
                'password' => 'required|min:1',
            ], [
                'name.required' => 'The name field is required.',
                'email.required' => 'The email field is required.',
                'email.email' => 'Invalid email format.',
                'email.unique' => 'The email address is already taken.',
                'password.required' => 'The password field is required.',
                'password.min' => 'The password must be at least 1 characters.',
            ]);



            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'user_type' => 1, // Assuming 1 represents a regular user
            ]);

            // Perform any additional actions or redirects here

            return redirect('/dashboard')->with('success', 'Sign up successful!');
        } catch (\Illuminate\Database\QueryException $exception) {
            $errorCode = $exception->errorInfo[1];
            if ($errorCode === 1062) { // Duplicate entry error code
                return back()->withInput()->withErrors(['email' => 'The email address is already taken.']);
            }

            // Handle other database errors if needed
            return back()->withInput()->withErrors(['unexpected_error' => 'An unexpected error occurred.']);
        }
    }

    public function login()
    {
        return view('login');
    }

    public function home()
    {
        return view('publicSearch');
    }
    public function searchingData(Request $request) {
        $codeforcesHandle = $request->input('codeforces');
        $vjudgeHandle = $request->input('vjudge');
        $spojHandle = $request->input('spoj');

        // print_r($codeforcesHandle);
        // print_r($vjudgeHandle);
        // print_r($spojHandle);
    
        // Process the received data or perform any necessary operations

        $path = base_path('app/scraping/publicCodeforces.php');

        exec("php \"$path\" \"$codeforcesHandle\"", $output);
        $jsonResponse = end($output);
        $data = json_decode($jsonResponse, true);
        // print_r($data);
        $paginatedData = collect($data)->paginate(10);
        return view('searchResultDashboard', compact('paginatedData')); 
        // return view('searchResultDashboard', compact('data'));
    }
    public function dashboard()
    {
        if (Auth::check()) {
            $user = Auth::user();
            if ($user->user_type == 1) {
                return view('dashboard');
            } else if ($user->user_type == 3) {
                return redirect('/teacher-dashboard');
            }
        } else {
            return redirect('/login');
        }
    }
    public function joinRequest()
    {
        return view('joinRequest');
    }
}
