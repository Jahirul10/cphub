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
Route::post('/teacher-dashboard', [TeacherController::class, 'dashboard']);

Route::get('/students/{id}', [StudentController::class, 'showSubmissionHistory']);

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
