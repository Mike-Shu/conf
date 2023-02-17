<?php

namespace App\Http\Livewire;

use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ShopCart extends Component
{
    protected $listeners = ['updateCart' => 'render'];

    public function render()
    {
        return view('livewire.shop-cart', [
            'entities' => Cart::content(),
        ]);
    }

    public function removeFromCart($productId)
    {
        Cart::remove($productId);
        $this->emit('updateCart');
    }
}
