<!-- Judul Lecture -->
<h1 class="text-3xl font-bold mb-4">{{ $lecture->title }}</h1>

<!-- Area Konten Dinamis -->
<div class="prose max-w-none">
    @if ($lecture->type === 'video' && $videoUrl)
        <!-- Jika ini adalah video, tampilkan pemutar video HTML5 -->
        <div class="aspect-video">
            <video controls controlsList="nodownload" class="w-full rounded-lg" src="{{ $videoUrl }}" oncontextmenu="return false;"></video>
        </div>
        <p class="text-sm text-gray-500 mt-2">Video uploaded on: {{ $lecture->created_at->format('d M Y') }}</p>
    @elseif ($lecture->type === 'text')
        <!-- Jika ini adalah teks, tampilkan kontennya -->
        <div class="p-4 border rounded-lg">
            {!! nl2br(e($lecture->content)) !!}
        </div>
    @else
        <p class="text-gray-500">No content available for this lecture.</p>
    @endif
</div>