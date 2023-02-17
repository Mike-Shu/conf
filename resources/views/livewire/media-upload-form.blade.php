<div>
    @if($header)
        <div class="flex font-bold items-center mb-6 text-gray-600">{{ $header }}</div>
    @endif

    @if($comment)
        <div class="mb-6 text-gray-600">{!! $comment !!}</div>
    @endif

    <x-filament::form wire:submit.prevent="submit">
        {{ $this->form }}
        <x-filament::button type="submit" form="submit">
            {{ $buttonSubmitText }}
        </x-filament::button>
    </x-filament::form>
</div>
