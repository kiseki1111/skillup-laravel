<x-learning>
    <!-- 1. Inisialisasi State Alpine.js -->
    <!-- 'lectureContent' akan menyimpan HTML yang kita ambil dari server. -->
    <!-- 'isLoading' untuk menampilkan indikator loading. -->
    <!-- 'activeLectureId' untuk menandai lecture mana yang sedang aktif di sidebar. -->
    <div x-data="{ lectureContent: '', isLoading: false, activeLectureId: null }" class="flex h-screen bg-gray-100">

        <!-- Sidebar Kurikulum -->
        <div class="w-80 bg-white shadow-lg overflow-y-auto">
            <div class="p-4 border-b">
                <h3 class="text-lg font-bold truncate">{{ $course->title }}</h3>
                <a href="{{ route('student.courses.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to My Courses</a>
            </div>

            <div class="mt-4">
                @foreach($course->sections as $section)
                    <div class="border-t">
                        <h4 class="px-4 py-3 font-semibold bg-gray-50 text-gray-700">{{ $section->title }}</h4>
                        <ul>
                            @foreach($section->lectures as $lecture)
                                <li class="border-t">
                                    <!-- 2. Membuat Tautan Interaktif -->
                                    <a href="{{ route('learn.lecture.content', [$course, $lecture]) }}"
                                       @click.prevent="
                                            isLoading = true;
                                            activeLectureId = {{ $lecture->id }};
                                            fetch($event.currentTarget.href)
                                                .then(response => response.text())
                                                .then(html => {
                                                    lectureContent = html;
                                                    isLoading = false;
                                                });
                                       "
                                       class="block px-4 py-3 text-sm flex items-center transition-colors"
                                       {{-- Menambahkan style aktif jika ID lecture cocok --}}
                                       :class="activeLectureId === {{ $lecture->id }} ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-800 hover:bg-gray-100'">
                                        
                                        @if($lecture->type == 'video')
                                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        @else
                                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                        @endif
                                        <span class="truncate">{{ $lecture->title }}</span>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Konten Utama (Area Belajar) -->
        <div class="flex-1 p-6 md:p-8 overflow-y-auto">
            <div class="bg-white p-6 rounded-lg shadow-md min-h-full">
                
                <!-- 3. Menampilkan Indikator Loading atau Konten -->
                <template x-if="isLoading">
                    <div class="flex justify-center items-center h-full">
                        <p class="text-gray-500">Loading lecture content...</p>
                    </div>
                </template>

                <!-- 'x-html' akan menyuntikkan HTML yang kita dapat dari server ke dalam div ini -->
                <div x-show="!isLoading" x-html="lectureContent" class="h-full">
                    <!-- Ini adalah konten default sebelum lecture dipilih -->
                    <div class="flex flex-col justify-center items-center h-full text-center">
                        <h1 class="text-3xl font-bold mb-4">Welcome to '{{ $course->title }}'!</h1>
                        <p class="text-gray-600">Please select a lecture from the sidebar on the left to begin your learning journey.</p>
                        <div class="mt-6 w-full max-w-lg aspect-video bg-black rounded-lg flex items-center justify-center">
                            <p class="text-white font-semibold">Select a lecture to play</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-learning>