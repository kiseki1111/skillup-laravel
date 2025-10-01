<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Section;
use illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    public function store(Request $request, Course $course)
    {
        if ($course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:256',
        ]);

        $course->sections()->create($validated);

        return back()->with('success', 'New section added successfully');
    }   

    public function update(Request $request, Section $section)
    {
        if ($section->course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $validated = $request->validate ([
            'title' => 'required|string|max:256',
        ]);

        $section->update($validated);

        return back()->with('success', 'Section updated successfully');
    }

    public function destroy(Section $section)
    {
        if ($section->course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION');
        }

        $section->delete();

        return back()->with('success', 'Section deleted successfully');
    }
}
