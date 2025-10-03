<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Explore Courses') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <h1 class="text-3xl font-bold mb-6">All Courses</h1>

                    <!-- Grid untuk Kartu Kursus -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        
                        @forelse ($courses as $course)
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl ...">
                                <!-- Bagian Gambar/Thumbnail -->
                                <!-- PERBAIKAN 1: Ganti href di sini -->
                                <a href="{{ route('courses.show', $course) }}">
                                    <img ...>
                                </a>

                                <!-- Bagian Konten Kartu -->
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold ...">
                                        <!-- PERBAIKAN 2: Ganti href di sini -->
                                        <a href="{{ route('courses.show', $course) }}" class="hover:text-blue-600">{{ $course->title }}</a>
                                    </h3>
                                    
                                    <!-- ... sisa konten kartu ... -->
                                </div>
                            </div>
                        @empty
                            <!-- ... -->
                        @endforelse

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>