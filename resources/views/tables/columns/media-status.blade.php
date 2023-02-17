@php
    $state = $getState();
@endphp

<div class="px-4 py-3">
    <div class="text-{{ $state['color'] }}-600">{{ $state['status'] }}</div>
    @if($state['reason'])
        <div class="flex items-center text-xs text-gray-600">
            <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
            <div>{{ $state['reason'] }}</div>
        </div>
    @endif
    @if($state['video_provider'])
        <div class="flex items-center text-xs text-gray-600">
            <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
            <div>{{ $state['video_provider'] }}</div>
        </div>
    @endif
</div>
