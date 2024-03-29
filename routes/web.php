<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\VisitorController;
use App\Http\Controllers\AuthController;

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


Route::get('/', function () {
    return redirect('/home');
});

Route::get('/signup', function () {
    return view('signUp');
});
Route::post('/signup', [VisitorController::class, 'signup']);

Route::get('/login', [AuthController::class, 'showLoginForm']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/home', [VisitorController::class, 'home']);
Route::get('/comparison-form', [VisitorController::class, 'showComparisonForm']);
Route::post('/showcomparison', [VisitorController::class, 'showComparison']);
Route::get('/search-result', [VisitorController::class, 'searchResult']);
Route::get('/dashboard', [VisitorController::class, 'dashboard']);

Route::get('/searchdata', [VisitorController::class, 'searchingData']);
Route::post('/searchdata', [VisitorController::class, 'searchingData']);

Route::get('/join-request', [VisitorController::class, 'showJoinRequestForm']);
Route::post('/join-request', [VisitorController::class, 'joinRequest']);

Route::get('/successful-request', [VisitorController::class, 'successfulRequest']);

Route::get('/add-student', [TeacherController::class, 'addStudent']);
Route::delete('/delete-request/{studentId}', [TeacherController::class, 'deleteRequest']);
Route::post('/accept-request/{studentId}', [TeacherController::class, 'acceptRequest']);

Route::get('/teacher-dashboard', [TeacherController::class, 'dashboard']);

Route::post('/teacher-table-update/{session}', [TeacherController::class, 'updateTable']);

Route::get('/student/{id}', [StudentController::class, 'showSubmissionHistory'])->name('student.show');

Route::post('/student/{id}', [TeacherController::class, 'studentSubmissionHistory']);

Route::post('/filter-submissions', [StudentController::class, 'filterSubmissions']);

Route::get('images/{filename}', function ($filename) {
    $path = public_path('images/' . $filename);
    echo $path;
    $permissions = fileperms($path);

    // Output the file permissions
    echo $permissions;
    if (file_exists($path)) {
        return response()->file($path);
    } else {
        abort(404);
    }
})->where('filename', '.*');
