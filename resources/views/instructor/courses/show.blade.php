{{-- Menggunakan layout utama dari Breeze --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{-- Judul Halaman: Manage Curriculum for [Nama Kursus] --}}
            {{ __('Manage Curriculum for: ') }} <span class="font-bold">{{ $course->title }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    {{-- Tombol untuk Kembali ke Daftar Kursus --}}
                    <div class="mb-6">
                        <a href="{{ route('instructor.courses.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            &larr; Back to All Courses
                        </a>
                    </div>

                    {{-- Tombol untuk Tambah Section Baru --}}
                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold">Course Curriculum</h3>
                        <a href="#" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            + Add New Section
                        </a>
                    </div>

                    {{-- Daftar Sections (Bab) --}}
                    <div class="space-y-6">
                        @forelse ($course->sections as $section)
                            <div class="bg-gray-100 p-4 rounded-lg shadow">
                                {{-- Judul Section dan Tombol Aksi --}}
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-xl font-bold">{{ $section->title }}</h4>
                                    <div class="flex items-center space-x-2">
                                        <a href="#" class="text-sm text-yellow-600 hover:text-yellow-900">Edit Section</a>
                                        <form action="#" method="POST" onsubmit="return confirm('Are you sure you want to delete this section?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Tombol untuk Tambah Lecture di dalam Section ini --}}
                                <div class="border-t border-gray-300 pt-3">
                                     <a href="#" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 text-sm rounded mb-3 inline-block">
                                        + Add New Lecture
                                    </a>

                                    {{-- Daftar Lectures (Materi) di dalam Section --}}
                                    <ul class="space-y-2 ml-4">
                                        @forelse ($section->lectures as $lecture)
                                            <li class="flex justify-between items-center bg-white p-2 rounded shadow-sm">
                                                <span>{{ $lecture->title }} <span class="text-xs text-gray-500">({{ $lecture->type }})</span></span>
                                                <div class="flex items-center space-x-2">
                                                    <a href="#" class="text-xs text-yellow-600 hover:text-yellow-900">Edit</a>
                                                    <form action="#" method="POST" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900">Delete</button>
                                                    </form>
                                                </div>
                                            </li>
                                        @empty
                                            <li class="text-gray-500 text-sm">No lectures in this section yet.</li>
                                        @endforelse
                                    </ul>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500">This course doesn't have any sections yet. Click "Add New Section" to get started.</p>
                        @endforelse
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
