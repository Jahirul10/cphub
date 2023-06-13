<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/student-dashboard',  'StudentController@index');
// Route::get('/student-dashboard', [StudentController::class, 'showStudent']);

Route::get('/teacher-dashboard', [TeacherController::class, 'dashboard']);

Route::get('/students/{id}', [StudentController::class, 'showSubmissionHistory']);