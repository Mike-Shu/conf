<div>
    @if($cartCount)
        <a href="{{ route('shop.cart') }}" class="is_icon" uk-tooltip="title: Корзина" title="Корзина" aria-expanded="false">
            @svg('ri-shopping-cart-line', 'h-8 w-8')
            <span>{{ $cartCount  }}</span>
        </a>
    @endif
</div>
