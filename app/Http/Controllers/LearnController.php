<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class LearnController extends Controller
{
    public function show()
    {
        $user = Auth::user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            abort(403, 'YOU NDK ENROLLED NAM');
        }
    }
}
