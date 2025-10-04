<h1 class="text-3xl font-bold mb-4">{{ $lecture->title }}</h1>

<div class="prose max-w-none">
    @if ($lecture->type === 'video' && $videoUrl)
        <div class="aspect-video">
            <video controls controlsList="nodownload" 
                   class="w-full rounded-lg" 
                   src="{{ $videoUrl }}" 
                   @ended="markAsComplete({{ $lecture->id }})"
                   oncontextmenu="return false;"></video>
        </div>
        <p class="text-sm text-gray-500 mt-2">Video uploaded on: {{ $lecture->created_at->format('d M Y') }}</p>

        <!-- === TAMBAHKAN TOMBOL MANUAL UNTUK VIDEO DI SINI === -->
        <div class="mt-6 text-right">
            <button @click="markAsComplete({{ $lecture->id }})" 
                    :disabled="completedLectures.includes({{ $lecture->id }})"
                    class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                
                <span x-show="!completedLectures.includes({{ $lecture->id }})">Mark as Complete</span>
                <span x-show="completedLectures.includes({{ $lecture->id }})">Completed!</span>
            </button>
        </div>

    @elseif ($lecture->type === 'text')
        <div class="p-4 border rounded-lg bg-gray-50">
            {!! nl2br(e($lecture->content)) !!}
        </div>
        <div class="mt-6 text-right">
            <button @click="markAsComplete({{ $lecture->id }})" 
                    :disabled="completedLectures.includes({{ $lecture->id }})"
                    class="bg-green-500 text-white font-bold py-2 px-4 rounded hover:bg-green-700 transition-colors disabled:bg-gray-400 disabled:cursor-not-allowed">
                
                <span x-show="!completedLectures.includes({{ $lecture->id }})">Mark as Complete</span>
                <span x-show="completedLectures.includes({{ $lecture->id }})">Completed!</span>
            </button>
        </div>
    @else
        <p class="text-gray-500">No content available for this lecture.</p>
    @endif
</div>