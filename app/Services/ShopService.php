<?php

namespace App\Services;

use App\Exceptions\BuyProductsException;
use App\Exceptions\ProductNotEnoughException;
use App\Exceptions\ProductNotFoundException;
use App\Models\ShopOrder;
use App\Models\ShopOrderItem;
use App\Models\ShopProduct;
use App\Models\User;
use Bavix\Wallet\Internal\Exceptions\ExceptionInterface;
use Bavix\Wallet\Objects\Cart;
use Exception;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;

/**
 * TODO: need refactoring
 */
class ShopService
{
    /**
     * @return mixed
     */
    public function getShopEntities(): LengthAwarePaginator
    {
        return ShopProduct::active()
            ->whereInStock()
            ->paginate(100);
    }

    /**
     * @param array $products
     *
     * @throws ProductNotFoundException
     * @throws ProductNotEnoughException
     */
    public function checkProductsAvailability(array $products): void
    {
        foreach ($products as $_item) {
            $productId = $_item['product_id'];

            $product = ShopProduct::find($productId);

            if (is_null($product)) {
                throw new ProductNotFoundException("Product {$productId} not found");
            }

            if (!$product->inStock($_item['amount'])) {
                throw new ProductNotEnoughException("{$product->title}: only {$product->stock} pieces left");
            }
        }
    }

    /**
     * @param array $cartData
     * @param User $user
     *
     * @throws BuyProductsException
     * @throws ExceptionInterface
     */
    public function buyProducts(array $cartData, User $user): void
    {
        DB::beginTransaction();

        try {
            $this->checkProductsAvailability($cartData['products']);
            $order = $this->makeOrder($cartData, $user);
            $this->purchaseProductsFromCart($order, $user);
            $this->updateStock($order);
        } catch (Exception $e) {
            DB::rollBack();
            throw new BuyProductsException($e->getMessage());
        }

        DB::commit();
    }

    /**
     * @param array $cartData
     * @param User $user
     *
     * @return ShopOrder
     */
    protected function makeOrder(array $cartData, User $user): ShopOrder
    {
        $order = ShopOrder::create([
            'user_id' => $user->id,
            'address' => $cartData['address'],
        ]);

        $cartProducts = array_map(static function ($_product) use ($order, $user) {
            $_product['order_id'] = $order->id;
            $_product['price'] = ShopProduct::find($_product['product_id'])->getAmountProduct($user);
            return new ShopOrderItem($_product);
        }, $cartData['products']);

        $order->items()->saveMany($cartProducts);

        return $order;
    }

    /**
     * @param ShopOrder $order
     * @param User $user
     * @throws BuyProductsException|ExceptionInterface
     */
    protected function purchaseProductsFromCart(ShopOrder $order, User $user): void
    {
        $cart = app(Cart::class);

        $orderItems = $order->items->all();

        foreach ($orderItems as $_item) {
            $product = ShopProduct::find($_item->product_id);
            $_item->product_details = $product->getMetaProduct();
            $_item->save();
            $cart = $cart->withItem($product, $_item->amount);
        }

        if ($cart->getTotal($user) > $user->balance) {
            throw new BuyProductsException('Недостаточно средств');
        }

        $user->payCart($cart);

        /*if (in_array($user->email, [
            'elizaveta970810@gmail.com',
            'steelpuppy@gmail.com',
            'mechalych@mail.ru',
            'vs@zorca.org',
        ]) ) {
            $user->payCart($cart);
        } else {
            throw new BuyProductsException('Покупка временно отключена');
        }*/
    }

    /**
     * @param ShopOrder $order
     */
    protected function updateStock(ShopOrder $order): void
    {
        $orderItems = $order->items->all();

        foreach ($orderItems as $_item) {
            ShopProduct::find($_item->product_id)->decreaseStock($_item->amount, [
                'description' => "Sold",
                'reference' => $_item,
            ]);
        }
    }
}
