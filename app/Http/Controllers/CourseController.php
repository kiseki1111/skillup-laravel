<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function index()
    {
        $courses = Cache::remember('courses.catalog', now()->addHour(), function() {
            return Course::latest()->with('instructor')->get();
        });
        return view('courses.index', compact('courses'));
    }
}
