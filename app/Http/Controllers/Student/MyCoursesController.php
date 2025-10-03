<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class MyCoursesController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $enrolledCourses = $user->enrolledCourses()->latest()->get();
        return view('student.my-courses.index', compact('enrolledCourses'));
    }
}
