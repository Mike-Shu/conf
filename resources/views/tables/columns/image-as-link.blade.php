@php
    $state = $getState();
@endphp

<div class="px-4 py-3">
    @if($state['src'])
        <a href="{{ $state['href'] }}" target="_blank">
            <img class="inline h-16" src="{{ $state['src'] }}" alt="">
        </a>
    @else
        <a href="{{ $state['href'] }}"
           target="_blank"
           class="text-primary-600 transition hover:underline hover:text-primary-500 focus:underline focus:text-primary-500">
            {{ __("Link") }}
        </a>
    @endif
</div>
