<?php

namespace App\Http\Controllers\instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Course;

class CourseController extends Controller
{
    public function index() 
    {
        $courses = Auth::user()->courses()->latest()->get();
        return view('instructor.courses.index', compact('courses'));
    }

    public function create ()
    {
        return view('instructor.courses.create');
    }

    public function store (Request $request)
    {
         $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        Auth::user()->courses()->create($validated);

        return redirect()->route('instructor.courses.index')->with('success', "Course successfully created");
    }

    public function edit(Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }
        return view('/instructor.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
        ]);

        $course->update($validated);

        return redirect()->route('instructor.courses.index')->with('success', 'Course successfully updated');
    }

    public function destroy(Course $course)
    {
         if ($course->user_id !== Auth::id()) {
            abort(403);
        }
        $course->delete();
        return redirect()->route('instructor.courses.index')->with('success', 'Course successfully deleted');
    }

    public function show(Course $course)
    {
        
        if ($course->user_id !== Auth::id()) {
            abort(403);
        }

        $course->load('sections.lectures');
        return view('instructor.courses.show', compact('course'));
    }
}
