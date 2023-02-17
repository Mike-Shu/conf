<?php

namespace App\Http\Livewire;

use App\Models\ShopProduct;
use Gloudemans\Shoppingcart\Facades\Cart;
use Livewire\Component;

class ShopProducts extends Component
{
    public $cartProducts = [];

    public function render()
    {
        return view('livewire.shop-products', [
            'entities' => ShopProduct::all()
        ]);
    }


    public function addToCart($productId)
    {
        $product = ShopProduct::find($productId);
        Cart::add([
            'id' => $productId,
            'name' => $product->title,
            'qty' => 1,
            'price' => $product->price,
            'weight' => 0,
            'options' => [
                'picture' => json_decode($product->firstPicture->content),
            ],
        ]);
        $this->cartProducts[] = $productId;
        $this->emit('updateCart');
    }

    public function removeFromCart($productId)
    {
        Cart::where('product_id', $productId)->delete();
        if (($key = array_search($productId, $this->cartProducts)) !== false) {
            unset($this->cartProducts[$key]);
        }
        $this->emit('updateCart');
    }
}
