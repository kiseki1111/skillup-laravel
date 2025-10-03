<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Lecture;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LearnController extends Controller
{
    public function show(Course $course)
    {
        $user = Auth::user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $course->load('sections.lectures');

        return view('learn.show', compact('course'));
    }

    public function getLectureContent(Course $course, Lecture $lecture)
    {
        $user = Auth::user();

        if (!$user->enrolledCourses()->where('course_id', $course->id)->exists()) {
            abort(403, 'You are not enrolled in this course.');
        }

        $videoUrl = null;

        if ($lecture->type === 'video' && $lecture->video_path) {
            $videoUrl = Storage::disk('s3')->temporaryUrl(
                $lecture->video_path,
                now()->addMinutes(15)
            );
        }

        return view('learn._lecture-content', compact('lecture', 'videoUrl'));
    }
}