<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Section;
use App\Models\Lecture;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;
// === PERBAIKAN 1: Gunakan nama kelas yang benar (dengan "ed") ===
use App\Jobs\ProcessUploadedVideo;

class LectureController extends Controller
{
    public function store(Request $request, Section $section)
    {
        if ($section->course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'type' => ['required', Rule::in(['video', 'text'])],
            'content' => 'nullable|string',
            'video_file' => 'required_if:type,video|file|mimes:mp4,mov|max:102400',
        ]);

        $lectureData = [
            'title' => $validated['title'],
            'type' => $validated['type'],
            'content' => $validated['content'] ?? null,
            'video_path' => null,
        ];

        if ($request->hasFile('video_file') && $validated['type'] === 'video') {
            $path = $request->file('video_file')->store('lecture_videos', 's3');
            $lectureData['video_path'] = $path;
        }

        $lecture = $section->lectures()->create($lectureData);

        if ($lecture->type === 'video') {
            // Panggilan ini sekarang akan berhasil karena 'use' statement di atas sudah benar
            ProcessUploadedVideo::dispatch($lecture);
        }

        return back()->with('success', 'New lecture added successfully!');
    }

    public function show(Lecture $lecture)
    {
        if ($lecture->section->course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $videoUrl = null;
        $thumbnailUrl = null;

        if ($lecture->type === 'video' && $lecture->video_path) {
            $videoUrl = Storage::disk('s3')->temporaryUrl(
                $lecture->video_path,
                now()->addMinutes(15)
            );
        }

        if ($lecture->thumbnail_path) {
            $thumbnailUrl = Storage::disk('s3')->temporaryUrl (
                $lecture->thumbnail_path,
                now()->addMinutes(15)
            );
        }

        return view('instructor.lectures.show', compact('lecture', 'videoUrl', 'thumbnailUrl'));
    }

    public function destroy(Lecture $lecture)
    {
        if ($lecture->section->course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        // === PERBAIKAN 2: Tambahkan logika untuk menghapus file dari MinIO ===
        if ($lecture->type === 'video' && $lecture->video_path) {
            Storage::disk('s3')->delete($lecture->video_path);
        }
        // Juga hapus thumbnail jika ada
        if ($lecture->thumbnail_path) {
            Storage::disk('s3')->delete($lecture->thumbnail_path);
        }

        $lecture->delete();

        // === PERBAIKAN 3: Perbaiki pesan sukses ===
        return back()->with('success', 'Lecture deleted successfully!');
    }

    public function update(Request $request, Lecture $lecture)
    {
        if ($lecture->section->course->user_id !== Auth::id()) {
            abort(403, 'UNAUTHORIZED ACTION.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content'=> 'nullable|string',
        ]);

        $lecture->update($validated);

        return back()->with('success', 'Lecture updated successfully!');
    }
}