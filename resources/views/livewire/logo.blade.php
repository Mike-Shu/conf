<div>
    @if($logo)
        <img src="{{ $logo }}" alt="Логотип проекта">
    @else
        <span>{{ __('Logo not found') }}</span>
    @endif
</div>
