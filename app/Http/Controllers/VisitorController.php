<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class VisitorController extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function publicSearch()
    {
        return view('publicSearch');
    }
}
