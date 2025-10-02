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
                            <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
                                <!-- Bagian Gambar/Thumbnail -->
                                <a href="#">
                                    {{-- Untuk saat ini kita gunakan placeholder. Nanti kita akan ganti dengan thumbnail kursus. --}}
                                    <img class="w-full h-48 object-cover" src="https://placehold.co/600x400/3498db/ffffff?text=SkillUp" alt="Course thumbnail">
                                </a>

                                <!-- Bagian Konten Kartu -->
                                <div class="p-4">
                                    <h3 class="text-lg font-semibold mb-2 h-14 overflow-hidden">
                                        <a href="#" class="hover:text-blue-600">{{ $course->title }}</a>
                                    </h3>
                                    
                                    <p class="text-sm text-gray-600 mb-4">
                                        By {{ $course->instructor->name ?? 'Unknown Instructor' }}
                                    </p>

                                    <div class="flex justify-between items-center">
                                        <p class="text-lg font-bold text-blue-500">
                                            Rp{{ number_format($course->price, 0, ',', '.') }}
                                        </p>
                                        {{-- Di sini nanti bisa ditambahkan rating bintang --}}
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-span-1 sm:col-span-2 lg:col-span-3 text-center text-gray-500 py-10">
                                <p>No courses available at the moment.</p>
                            </div>
                        @endforelse

                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>