@php
    $state = $getState();
@endphp

<div class="filament-tables-text-column px-4 py-3">
    @if($state['start_datetime'])
        <div class="flex items-center space-x-1">
            <x-heroicon-o-calendar class="w-5 h-5 text-success-700"/>
            <div>{{ $state['start_datetime'] }}</div>
        </div>
    @endif

    @if($state['finish_datetime'])
        <div class="flex items-center space-x-1">
            <x-heroicon-o-calendar class="w-5 h-5 text-primary-700"/>
            <div>{{ $state['finish_datetime'] }}</div>
        </div>
    @endif
</div>
