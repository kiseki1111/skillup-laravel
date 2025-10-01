<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Viewing Lecture: <span class="font-bold">{{ $lecture->title }}</span>
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    <div class="mb-6">
                        <a href="{{ route('instructor.courses.show', $lecture->section->course) }}" class="text-blue-600 hover:text-blue-900 font-medium">
                            &larr; Back to Curriculum
                        </a>
                    </div>

                    <h3 class="text-2xl font-semibold mb-4">{{ $lecture->title }}</h3>

                    <!-- ====================================================== -->
                    <!--     MULAI BAGIAN UTAMA YANG DIROMBAK                 -->
                    <!-- ====================================================== -->
                    @if ($lecture->type === 'video')
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Kolom Kiri: Thumbnail -->
                            <div class="md:col-span-2">
                                @if ($thumbnailUrl)
                                    <img src="{{ $thumbnailUrl }}" alt="Video thumbnail" class="w-full rounded-lg shadow-md">
                                @else
                                    <div class="w-full aspect-video bg-gray-200 rounded-lg flex items-center justify-center">
                                        <p class="text-gray-500">Thumbnail is being processed...</p>
                                    </div>
                                @endif
                            </div>

                            <!-- Kolom Kanan: Metadata -->
                            <div>
                                <h4 class="text-lg font-bold mb-3 border-b pb-2">Video Details</h4>
                                <ul class="space-y-2 text-sm">
                                    <li>
                                        <strong class="font-semibold">Upload Date:</strong>
                                        <span>{{ $lecture->created_at->format('d M Y, H:i') }}</span>
                                    </li>
                                    <li>
                                        <strong class="font-semibold">Duration:</strong>
                                        @if ($lecture->duration_in_seconds)
                                            <!-- Format durasi dari detik menjadi Menit:Detik -->
                                            <span>{{ gmdate('i:s', $lecture->duration_in_seconds) }} minutes</span>
                                        @else
                                            <span class="text-gray-500">Processing...</span>
                                        @endif
                                    </li>
                                    <li>
                                        <strong class="font-semibold">File Path:</strong>
                                        <span class="break-all text-gray-600">{{ $lecture->video_path ?? 'N/A' }}</span>
                                    </li>
                                </ul>
                                @if($videoUrl)
                                    <a href="{{ $videoUrl }}" target="_blank" class="mt-4 inline-block bg-blue-500 text-white font-bold py-2 px-4 rounded hover:bg-blue-700">
                                        Watch Full Video
                                    </a>
                                @endif
                            </div>
                        </div>

                    @elseif ($lecture->type === 'text')
                        <div class="prose max-w-none">
                            <h4 class="text-lg font-bold mb-3 border-b pb-2">Article Content</h4>
                            <div>
                                {!! nl2br(e($lecture->content)) !!}
                            </div>
                        </div>
                    @else
                        <p class="text-gray-500">No content available for this lecture.</p>
                    @endif
                    <!-- ====================================================== -->
                    <!--     AKHIR BAGIAN UTAMA YANG DIROMBAK                  -->
                    <!-- ====================================================== -->

                </div>
            </div>
        </div>
    </div>
</x-app-layout>