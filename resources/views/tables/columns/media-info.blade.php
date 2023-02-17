@php
    $state = $getState();
@endphp

<div class="px-4 py-3">
    @if($state['description'])
        <div>{!! $state['description'] !!}</div>
    @endif
    @if($state['comment'])
        <div class="flex items-center text-xs text-gray-600">
            <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
            <div>{{ $state['comment'] }}</div>
        </div>
    @endif
</div>
