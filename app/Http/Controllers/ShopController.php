<?php

namespace App\Http\Controllers;

use App\Models\ShopProduct;
use App\Services\ShopService;
use Exception;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Contracts\Support\Renderable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class ShopController extends Controller
{
    protected ShopService $shopService;

    public function __construct(ShopService $shopService)
    {
        $this->shopService = $shopService;
    }

    /**
     * Returns the home page of the store.
     *
     * @param Request $request
     */
    public function index(Request $request): \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
    {
        $entities = $this->shopService->getShopEntities($request->user());

        return view('shop.index', [
            'entities' => $entities
        ]);
    }

    public function cart()
    {
        return view('shop.cart');
    }

    /**
     * Returns the checkout page.
     *
     * @return Response
     */
    public function checkout(): Response
    {
        return Inertia::render("modules:shop:Tenant/Views/Checkout/Index");
    }

    /**
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function purchase(Request $request): RedirectResponse
    {
        $tenant = tenant();

        if ($tenant && $tenant->shop_allow_cart) {
            $validated = $request->validate([
                'products' => "required|array",
                'products.*.product_id' => "required|integer|min:1|exists:WeconfModules\Shop\Entities\ShopProduct,id",
                'products.*.price' => "required|numeric|min:1",
                'products.*.amount' => "required|integer|min:0",
                'address' => "nullable|string|max:512"
            ]);

            try {
                $user = $request->user();
                $this->shopService->buyProducts($validated, $user);
            } catch (Exception $e) {
                logger("Purchase error: " . $e->getMessage());
                return back()->withErrors([
                    'message' => $e->getMessage()
                ]);
            }

            return Redirect::route('tenant.shop.index')->with('success', 'The purchase was successful');
        }

        logger("Purchase error: purchase closed");
        return back()->withErrors([
            'message' => __("Purchase closed"),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Renderable
     */
    public function create()
    {
        return view('shop::create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return Renderable
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function show($id)
    {
        return view('shop::show');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function edit($id)
    {
        return view('shop::edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int $id
     *
     * @return Renderable
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Renderable
     */
    public function destroy($id)
    {
        //
    }
}
