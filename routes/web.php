<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CourseController; // Controller publik
use App\Http\Controllers\Instructor\CourseController as InstructorCourseController; // Controller instruktur
use App\Http\Controllers\Instructor\SectionController;
use App\Http\Controllers\Instructor\LectureController;

// Rute publik untuk halaman katalog kursus
Route::get('/courses', [CourseController::class, 'index'])->name('courses.index');

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

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return '<h1>Welcome to dashboard</h1>';
    })->name('dashboard');
});

// Route untuk instruktur
Route::middleware(['auth', 'instructor'])->prefix('instructor')->name('instructor.')->group(function() {
    // Menggunakan alias 'InstructorCourseController' untuk menghindari konflik nama
    Route::get('/courses', [InstructorCourseController::class, 'index'])->name('courses.index');
    Route::get('/courses/create', [InstructorCourseController::class, 'create'])->name('courses.create');
    Route::post('/courses', [InstructorCourseController::class, 'store'])->name('courses.store');
    Route::get('/courses/{course}', [InstructorCourseController::class, 'show'])->name('courses.show');
    Route::get('/courses/{course}/edit', [InstructorCourseController::class, 'edit'])->name('courses.edit');
    Route::put('/courses/{course}', [InstructorCourseController::class, 'update'])->name('courses.update');
    Route::delete('/courses/{course}', [InstructorCourseController::class, 'destroy'])->name('courses.destroy');

    // Section Routes
    Route::post('/courses/{course}/sections', [SectionController::class, 'store'])->name('sections.store');
    Route::put('/sections/{section}', [SectionController::class, 'update'])->name('sections.update');
    Route::delete('/sections/{section}', [SectionController::class, 'destroy'])->name('sections.destroy');

    // Lecture Routes
    Route::post('/sections/{section}/lectures', [LectureController::class, 'store'])->name('lectures.store');
    Route::get('/lectures/{lecture}', [LectureController::class, 'show'])->name('lectures.show');
    Route::put('/lectures/{lecture}', [LectureController::class, 'update'])->name('lectures.update');
    Route::delete('/lectures/{lecture}',[LectureController::class, 'destroy'])->name('lectures.destroy');
});

require __DIR__.'/auth.php';