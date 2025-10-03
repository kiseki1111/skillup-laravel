<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class EnrollmentController extends Controller
{
    public function store(Request $request, Course $course)
    {
        $user = Auth::user();
        if ($course->instructor->id === $user->id) {
            return back()->with('error', 'You cannot enroll nam, kan kau yg punye');
        }

        if ($user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            return back()->with('error', 'Udah enrolled dah nam');
        }

        $user->enrolledCourses()->attach($course->id);
        return redirect()->route('student.courses.index')->with('success', "You successfully enrolled in '{$course->title}'");
    }
}
