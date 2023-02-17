@php
    $state = $getState();
@endphp

<div class="px-4 py-3">
    @if($state['type'] === \App\Enums\MediaType::IMAGE)
        <img class="inline h-16" src="{{ $state['content']['thumbnail-square'] }}" alt="">
    @else
        <div class="flex items-center">
            <x-heroicon-o-video-camera class="w-6 h-6 text-gray-400 mr-1"/>
            <a href="{{ $state['content']['file'] }}"
               target="_blank"
               class="text-primary-600 transition hover:underline hover:text-primary-500 focus:underline focus:text-primary-500">
                {{ __("File link") }}
            </a>
        </div>
    @endif
</div>
