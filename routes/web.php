<?php

use App\Http\Controllers\Instructor\CourseController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Instructor\SectionController;
use App\Http\Controllers\Instructor\LectureController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'admin']) -> prefix('admin') ->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Welcome to dashboard</h1>';
    })->name('dashboard');
});

Route::middleware(['auth', 'instructor'])->prefix('instructor')->name('instructor.')->group(function() {
    Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [CourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [CourseController::class, 'store'])->name('courses.store');
    Route::delete('/courses/{course}', [CourseController::class, 'destroy'])->name('courses.destroy');
    Route::get('/courses/{course}/edit', [CourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [CourseController::class, 'update'])->name('courses.update');
    Route::get('/courses/{course}', [CourseController::class, 'show'])->name('courses.show');
    Route::post('/courses/{course}/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::post('/sections/{section}/lectures', [LectureController::class, 'store'])->name('lectures.store');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');
    Route::get('/lectures/{lecture}', [LectureController::class, 'show'])->name('lectures.show');
    Route::put('lecture/{lecture}', [LectureController::class, 'update'])->name('lectures.update');
    Route::delete('lecture{lecture}',[LectureController::class, 'destroy'])->name('lectures.destroy');
});

require __DIR__.'/auth.php';
