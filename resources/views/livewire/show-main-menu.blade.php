<div>
    @if($menu)
        <ul>
            @foreach($menu->items as $item)
                @if($item['data'])
                    @if($item['type'] === 'page')
                        @if($item['data']['slug'])
                            <li class="{{ (request()->segment(1) === $item['data']['slug']) ? 'active' : '' }}">
                                <a href="{{ route('pages.show', ['page' => $item['data']['slug']]) }}">
                                    @if($item['icon'])
                                        @svg($item['icon'])
                                    @else
                                        @svg('ri-terminal-line')
                                    @endif
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endif
                    @else
                        @if($item['data']['url'])
                            <li>
                                <a href="{{ $item['data']['url'] }}">
                                    @if($item['icon'])
                                        @svg($item['icon'])
                                    @else
                                        @svg('ri-links-line')
                                    @endif
                                    <span>{{ $item['label'] }}</span>
                                </a>
                            </li>
                        @endif
                    @endif
                @endif
            @endforeach
        </ul>
    @else
        <div>{{ __('Menu not found') }}</div>
    @endif
</div>
