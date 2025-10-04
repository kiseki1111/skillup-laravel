<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lecture;
use App\Models\User;
use Illuminate\Support\Facades\auth;

class LectureCompletionController extends Controller
{
    public function store(Request $request, Lecture $lecture)
    {
        $user = Auth::user();

        $isEnrolled = $user->enrolledCourses()->where('courses.id', $lecture->section->course_id)->exists(); 
        if (!$isEnrolled)  {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $user->completedLectures()->syncWithoutDetaching($lecture->id);

        return response()->json(['message'=> 'Lectured marked as completed']);
    }
}
