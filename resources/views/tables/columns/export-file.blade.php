@php
    $state = $getState();
@endphp

<div class="filament-tables-text-column px-4 py-3 whitespace-normal">
    <div class="flex items-center space-x-1">
        @if($state['batch_finished'])
            @if($state['file_url'])
                <a href="{{ $state['file_url'] }}"
                   target="_blank"
                   onclick="window.event.stopPropagation()" {{-- TODO: event is deprecated --}}
                   class="text-primary-600 transition hover:underline hover:text-primary-500 focus:underline focus:text-primary-500">
                    {{ __('Download') }}
                </a>
                @if($state['file_size'])
                    <span class="text-xs">{{ $state['file_size'] }}</span>
                @endif
            @else
                {{ __('File not found') }}
            @endif
        @else
            <span>{{ __('Processing') }}</span>
            @if($state['batch_progress'])
                <span class="text-xs">{{ $state['batch_progress'] }}%</span>
            @else
                <span>&hellip;</span>
            @endif
        @endif
    </div>
</div>
