@php
    $state = $getState();
@endphp

<div class="px-4 py-3">
    <div>{{ $state['template'] }}</div>

    @if($state['player'])
        <div class="flex items-center text-sm text-gray-400">
            <x-heroicon-o-play class="h-4 w-4 shrink-0"/>
            <span class="ml-1">{{ $state['player'] }}</span>
        </div>
    @endif

    @if($state['chat'])
        <div class="flex items-center text-sm text-gray-400">
            <x-heroicon-o-chat class="h-4 w-4 shrink-0"/>
            <span class="ml-1">{{ $state['chat'] }}</span>
        </div>
    @endif
</div>
