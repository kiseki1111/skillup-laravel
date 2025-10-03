<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $course->title }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8 text-gray-900">

                    <!-- Tata Letak Dua Kolom -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        
                        <!-- Kolom Kiri: Deskripsi dan Kurikulum -->
                        <div class="md:col-span-2">
                            <h1 class="text-3xl font-bold mb-2">{{ $course->title }}</h1>
                            <p class="text-gray-600 mb-4">Created by {{ $course->instructor->name }}</p>

                            <div class="prose max-w-none mb-8">
                                <h2 class="text-2xl font-semibold mb-3">Description</h2>
                                <p>{{ $course->description }}</p>
                            </div>

                            <div class="prose max-w-none">
                                <h2 class="text-2xl font-semibold mb-3">Course Curriculum</h2>
                                <div class="space-y-4">
                                    @foreach ($course->sections as $section)
                                        <div class="border rounded-lg">
                                            <div class="bg-gray-100 p-4 font-bold rounded-t-lg">
                                                {{ $section->title }}
                                            </div>
                                            <ul class="divide-y">
                                                @foreach ($section->lectures as $lecture)
                                                    <li class="p-4 flex items-center">
                                                        <!-- Ikon sederhana berdasarkan tipe lecture -->
                                                        @if($lecture->type == 'video')
                                                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                                        @else
                                                            <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                        @endif
                                                        <span>{{ $lecture->title }}</span>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <!-- Kolom Kanan: Kartu Pembelian -->
                        <div class="md:col-span-1">
                            <div class="bg-gray-50 rounded-lg shadow-md p-6 sticky top-8">
                                <img class="w-full rounded-lg mb-4" src="https://placehold.co/600x400/3498db/ffffff?text=SkillUp" alt="Course thumbnail">
                                <p class="text-3xl font-bold text-blue-500 mb-4">
                                    Rp{{ number_format($course->price, 0, ',', '.') }}
                                </p>
                                <!-- ... di dalam kolom kanan ... -->

                                <!-- === GANTI TOMBOL LAMA DENGAN FORM INI === -->
                                <form action="{{ route('courses.enroll', $course) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-lg hover:bg-blue-700 transition-colors">
                                        Enroll in Course
                                    </button>
                                </form>
                                <p class="text-xs text-gray-500 text-center mt-3">30-Day Money-Back Guarantee</p>
                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>