<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('My Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <!-- Tampilkan pesan sukses setelah mendaftar -->
                    @if (session('success'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                            <p class="font-bold">Success</p>
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <h1 class="text-2xl font-bold mb-4">Courses You Are Enrolled In</h1>

                    <div class="divide-y">
                        <!-- Lakukan perulangan pada data yang dikirim dari controller -->
                        @forelse ($enrolledCourses as $course)
                            <div class="py-4 flex justify-between items-center">
                                <div>
                                    <h3 class="text-lg font-semibold">{{ $course->title }}</h3>
                                    <p class="text-sm text-gray-600">by {{ $course->instructor->name }}</p>
                                </div>
                                <div>
                                    <!-- Tautan ini akan kita fungsikan di tahap berikutnya -->
                                    <a href="{{ route('learn.show', $course) }}" class="bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700 transition-colors">
                                        Start Learning &rarr;
                                    </a>
                                </div>
                            </div>
                        @empty
                            <p class="text-gray-500 py-4">You are not enrolled in any courses yet. <a href="{{ route('courses.index') }}" class="text-blue-500 hover:underline">Browse courses now!</a></p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>