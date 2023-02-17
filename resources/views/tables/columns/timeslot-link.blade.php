@php
    $state = $getState();
@endphp

<div class="filament-tables-text-column px-4 py-3 whitespace-normal">
    @if($state['link'])
        <div class="flex items-center space-x-1">
            <x-heroicon-s-external-link class="w-5 h-5 text-gray-500"/>
            <a href="{{ $state['link'] }}"
               target="_blank"
               onclick="window.event.stopPropagation()" {{-- TODO: event is deprecated --}}
               class="text-primary-600 transition hover:underline hover:text-primary-500 focus:underline focus:text-primary-500">
                {{ str($state['anchor'] ?: $state['link'])->limit()->value() }}
            </a>
        </div>
    @endif
</div>
