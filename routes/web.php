<?php

use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

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
    return view('welcome');
});


Route::get('api/users', [UserController::class, 'index']);

############ Students Routes #################
Route:: prefix('students')->group(function(){
    Route::get('/', [StudentController::class, 'getStudents'])-> name('students');
    Route::post('/create', [StudentController::class, 'store'])-> name('students.create');
    // Route::get('/get-courses/{id}', [StudentController::class, 'getCourses'])-> name('students.getcourses');
    // Route::get('/edit/{id}', [StudentController::class,'edit'])->name('students.edit');
     Route::put('/update/{student}', [StudentController::class,'update'])->name('students.update');
    Route::delete('/delete/{id}', [StudentController::class,'delete'])->name('students.delete');
    Route::get('/search', [StudentController::class, 'search']);
    Route::get('/levels', [StudentController::class, 'getLevels']);
    Route::post('/filter/{id}', [StudentController::class, 'filter']);
});

##################### Courses Routes ###########################
Route::prefix('courses') -> group(function(){
    Route::get('/', [CourseController::class, 'index'])-> name('courses');
    Route::get('/search', [CourseController::class, 'search']);
    // Route::get('/get-students/{id}', [CourseController::class, 'getStudents'])-> name('courses.getstudents');
    // Route::get('/edit/{id}', [CourseController::class,'edit'])->name('courses.edit');
    // Route::post('/update/{id}', [CourseController::class,'update'])->name('courses.update');
    // Route::get('/delete/{id}', [CourseController::class,'delete'])->name('courses.delete');
     Route::get('/students/{id}', [CourseController::class,'getStudents'])->name('courses.getstudents');
});

Route::get('{view}', ApplicationController::class)->where('view','(.*)');

