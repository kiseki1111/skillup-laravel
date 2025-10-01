<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manage Curriculum for: ') }} <span class="font-bold">{{ $course->title }}</span>
        </h2>
    </x-slot>

    <div x-data="{ openModal: null }" class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                    <p class="font-bold">Success</p>
                    <p>{{ session('success') }}</p>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="mb-6">
                        <a href="{{ route('instructor.courses.index') }}" class="text-blue-600 hover:text-blue-900 font-medium">&larr; Back to All Courses</a>
                    </div>

                    <div class="mb-6 flex justify-between items-center">
                        <h3 class="text-2xl font-semibold">Course Curriculum</h3>
                        <button @click="openModal = 'add-section'" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">+ Add New Section</button>
                    </div>

                    <div class="space-y-6">
                        @forelse ($course->sections as $section)
                            <div class="bg-gray-100 p-4 rounded-lg shadow">
                                <div class="flex justify-between items-center mb-4">
                                    <h4 class="text-xl font-bold">{{ $section->title }}</h4>
                                    <div class="flex items-center space-x-3">
                                        <button @click="openModal = 'edit-section-{{ $section->id }}'" class="text-sm text-yellow-600 hover:text-yellow-900 font-semibold">Edit</button>
                                        <form action="{{ route('instructor.sections.destroy', $section) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 font-semibold">Delete</button>
                                        </form>
                                    </div>
                                </div>

                                <div class="border-t border-gray-300 pt-3">
                                    <button @click="openModal = 'add-lecture-{{ $section->id }}'" class="bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-3 text-sm rounded mb-3">
                                        + Add New Lecture
                                    </button>
                                    <ul class="space-y-2 ml-4">
                                        @forelse ($section->lectures as $lecture)
                                            <li class="bg-white p-2 rounded shadow-sm flex justify-between items-center">
                                                <a href="{{ route('instructor.lectures.show', $lecture) }}" class="flex-grow">
                                                    <span>{{ $lecture->title }} <span class="text-xs text-gray-500">({{ $lecture->type }})</span></span>
                                                </a>
                                                <div class="flex items-center space-x-3 flex-shrink-0 ml-4">
                                                    <button @click="openModal = 'edit-lecture-{{ $lecture->id }}'" class="text-xs text-yellow-600 hover:text-yellow-900 font-semibold">Edit</button>
                                                    <form action="{{ route('instructor.lectures.destroy', $lecture) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-xs text-red-600 hover:text-red-900 font-semibold">Delete</button>
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
                            <p class="text-gray-500">This course doesn't have any sections yet.</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal untuk Tambah Section Baru -->
        <div x-show="openModal === 'add-section'" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div @click.away="openModal = null" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                <h2 class="text-2xl font-bold mb-4">Add New Section</h2>
                <form action="{{ route('instructor.sections.store', $course) }}" method="POST">
                    @csrf
                    <input type="text" name="title" class="mt-1 block w-full rounded-md" placeholder="e.g., Chapter 1: Introduction" required>
                    <div class="mt-6 flex justify-end space-x-3">
                        <button type="button" @click="openModal = null" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">Cancel</button>
                        <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">Save Section</button>
                    </div>
                </form>
            </div>
        </div>

        @foreach ($course->sections as $section)
            <!-- Modal untuk Edit Section -->
            <div x-show="openModal === 'edit-section-{{ $section->id }}'" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div @click.away="openModal = null" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                    <h2 class="text-2xl font-bold mb-4">Edit Section Title</h2>
                    <form action="{{ route('instructor.sections.update', $section) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <input type="text" name="title" class="mt-1 block w-full rounded-md" value="{{ $section->title }}" required>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="openModal = null" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">Cancel</button>
                            <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Update Section</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- ====================================================== -->
            <!--     PERBAIKAN UTAMA ADA DI DALAM MODAL INI           -->
            <!-- ====================================================== -->
            <!-- Modal untuk Tambah Lecture -->
            <div x-show="openModal === 'add-lecture-{{ $section->id }}'" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                <div @click.away="openModal = null" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                    <h2 class="text-2xl font-bold mb-4">Add Lecture to: <span class="font-normal">{{ $section->title }}</span></h2>
                    <!-- Pastikan semua isi form di bawah ini ada -->
                    <form action="{{ route('instructor.lectures.store', $section) }}" method="POST" x-data="{ lectureType: 'video' }" enctype="multipart/form-data">
                        @csrf
                        <div class="space-y-4">
                            <div>
                                <label for="lecture_title_{{ $section->id }}" class="block text-sm font-medium text-gray-700">Lecture Title</label>
                                <input type="text" name="title" id="lecture_title_{{ $section->id }}" class="mt-1 block w-full rounded-md border-gray-300" placeholder="e.g., What is Laravel?" required>
                            </div>
                            <div>
                                <label for="lecture_type_{{ $section->id }}" class="block text-sm font-medium text-gray-700">Lecture Type</label>
                                <select name="type" id="lecture_type_{{ $section->id }}" x-model="lectureType" class="mt-1 block w-full rounded-md border-gray-300">
                                    <option value="video">Video</option>
                                    <option value="text">Text Article</option>
                                </select>
                            </div>
                            <div x-show="lectureType === 'text'">
                                <label for="lecture_content_{{ $section->id }}" class="block text-sm font-medium text-gray-700">Content</label>
                                <textarea name="content" id="lecture_content_{{ $section->id }}" rows="5" class="mt-1 block w-full rounded-md border-gray-300" placeholder="Write your article content here..."></textarea>
                            </div>
                            <div x-show="lectureType === 'video'">
                                <label for="video_file_{{ $section->id }}" class="block text-sm font-medium text-gray-700">Video File</label>
                                <input type="file" name="video_file" id="video_file_{{ $section->id }}" class="mt-1 block w-full text-sm">
                                @error('video_file')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end space-x-3">
                            <button type="button" @click="openModal = null" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">Cancel</button>
                            <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Save Lecture</button>
                        </div>
                    </form>
                </div>
            </div>

            @foreach ($section->lectures as $lecture)
                <!-- Modal untuk Edit Lecture -->
                <div x-show="openModal === 'edit-lecture-{{ $lecture->id }}'" x-cloak class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
                    <div @click.away="openModal = null" class="bg-white rounded-lg shadow-xl p-6 w-full max-w-md">
                        <h2 class="text-2xl font-bold mb-4">Edit Lecture: <span class="font-normal">{{ $lecture->title }}</span></h2>
                        <form action="{{ route('instructor.lectures.update', $lecture) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="space-y-4">
                                <div>
                                    <label for="edit_lecture_title_{{ $lecture->id }}" class="block text-sm font-medium text-gray-700">Lecture Title</label>
                                    <input type="text" name="title" id="edit_lecture_title_{{ $lecture->id }}" class="mt-1 block w-full rounded-md" value="{{ $lecture->title }}" required>
                                </div>
                                @if ($lecture->type === 'text')
                                <div>
                                    <label for="edit_lecture_content_{{ $lecture->id }}" class="block text-sm font-medium text-gray-700">Content</label>
                                    <textarea name="content" id="edit_lecture_content_{{ $lecture->id }}" rows="5" class="mt-1 block w-full rounded-md">{{ $lecture->content }}</textarea>
                                </div>
                                @endif
                            </div>
                            <div class="mt-6 flex justify-end space-x-3">
                                <button type="button" @click="openModal = null" class="bg-gray-200 text-gray-700 font-bold py-2 px-4 rounded">Cancel</button>
                                <button type="submit" class="bg-yellow-500 hover:bg-yellow-700 text-white font-bold py-2 px-4 rounded">Update Lecture</button>
                            </div>
                        </form>
                    </div>
                </div>
            @endforeach
        @endforeach
    </div>
</x-app-layout>