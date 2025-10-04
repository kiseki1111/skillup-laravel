<x-learning>
    <div x-data="{ 
            lectureContent: '', 
            isLoading: false, 
            activeLectureId: null,
            completedLectures: {{ json_encode($completedLecturesArray) }},
            totalLectures: {{ $totalLecturesCount }},
            markAsComplete(lectureId) {
                fetch(`/lectures/${lectureId}/complete`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content,
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data.message);
                    if (!this.completedLectures.includes(lectureId)) {
                        this.completedLectures.push(lectureId);
                    }
                });
            },
            get progressPercentage() {
                if (this.totalLectures === 0) return 0;
                return Math.round((this.completedLectures.length / this.totalLectures) * 100);
            }
         }" 
         class="flex h-screen bg-gray-100">

        <!-- Sidebar Kurikulum -->
        <div class="w-80 bg-white shadow-lg overflow-y-auto">
             <div class="p-4 border-b">
                <h3 class="text-lg font-bold truncate">{{ $course->title }}</h3>
                <a href="{{ route('student.courses.index') }}" class="text-sm text-blue-600 hover:underline">&larr; Back to My Courses</a>
                <div class="mt-4">
                    <div class="flex justify-between items-center mb-1 text-sm">
                        <span class="text-gray-600">Your Progress</span>
                        <span class="font-semibold text-blue-600" x-text="`${progressPercentage}%`"></span>
                    </div>
                    <div class="w-full bg-gray-200 rounded-full h-2.5">
                        <div class="bg-blue-600 h-2.5 rounded-full transition-all duration-500" :style="`width: ${progressPercentage}%`"></div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                @forelse($course->sections as $section)
                    <div class="border-t">
                        <h4 class="px-4 py-3 font-semibold bg-gray-50 text-gray-700">{{ $section->title }}</h4>
                        <ul>
                            @forelse($section->lectures as $lecture)
                                <li class="border-t">
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
                                       class="block px-4 py-3 text-sm flex items-center justify-between transition-colors"
                                       :class="activeLectureId === {{ $lecture->id }} ? 'bg-blue-100 text-blue-700 font-semibold' : 'text-gray-800 hover:bg-gray-100'">
                                        <div class="flex items-center min-w-0">
                                            @if($lecture->type == 'video')
                                                <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            @else
                                                <svg class="w-5 h-5 mr-3 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            @endif
                                            <span class="truncate">{{ $lecture->title }}</span>
                                        </div>
                                        <template x-if="completedLectures.includes({{ $lecture->id }})">
                                            <svg class="w-5 h-5 text-green-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                        </template>
                                    </a>
                                </li>
                            @empty
                                <li class="px-4 py-3 text-sm text-gray-500">No lectures in this section yet.</li>
                            @endforelse
                        </ul>
                    </div>
                @empty
                    <div class="px-4 py-3 text-sm text-gray-500">
                        The curriculum for this course is not available yet.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- ====================================================== -->
        <!--     PERBAIKAN UTAMA ADA DI DALAM KONTEN UTAMA INI      -->
        <!-- ====================================================== -->
        <!-- Konten Utama (Area Belajar) -->
        <div class="flex-1 p-6 md:p-8 overflow-y-auto">
             <div class="bg-white p-6 rounded-lg shadow-md min-h-full">
                <!-- Keadaan 1: Sedang Memuat -->
                <template x-if="isLoading">
                    <div class="flex justify-center items-center h-full">
                        <p class="text-gray-500 animate-pulse">Loading lecture content...</p>
                    </div>
                </template>

                <!-- Keadaan 2: Konten Sudah Ada -->
                <!-- x-show digunakan agar div tidak hilang-timbul, hanya disembunyikan -->
                <!-- 'prose' adalah class dari plugin Tailwind Typography untuk styling teks -->
                <div x-show="!isLoading && lectureContent" x-html="lectureContent" class="prose max-w-none">
                    <!-- Konten dari AJAX akan disuntikkan di sini -->
                </div>

                <!-- Keadaan 3: Keadaan Awal (Default) -->
                <template x-if="!isLoading && !lectureContent">
                    <div class="flex flex-col justify-center items-center h-full text-center">
                        <h1 class="text-3xl font-bold mb-4">Welcome to '{{ $course->title }}'!</h1>
                        <p class="text-gray-600">Please select a lecture from the sidebar on the left to begin your learning journey.</p>
                        <div class="mt-6 w-full max-w-lg aspect-video bg-black rounded-lg flex items-center justify-center">
                            <p class="text-white font-semibold">Select a lecture to play</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
</x-learning>