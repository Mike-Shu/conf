<?php

declare(strict_types=1);

use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\UserWalletController;
use App\Http\Controllers\WelcomeController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

/*
|--------------------------------------------------------------------------
| Tenant Routes
|--------------------------------------------------------------------------
|
| Here you can register the tenant routes for your application.
| These routes are loaded by the TenantRouteServiceProvider.
|
| Feel free to customize them however you want. Good luck!
|
*/

Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('/', [WelcomeController::class, 'show'])
        ->middleware(['guest:'.config('fortify.guard')])
        ->name('welcome');

    Route::middleware([
        'auth:sanctum',
        config('jetstream.auth_session'),
        'verified'
    ])->group(function () {
        Route::get('dashboard', [PageController::class, 'showMain'])->name('pages.show-main');

        Route::get('shop', [ShopController::class, 'index'])->name('shop.index');
        Route::get('shop/cart', [ShopController::class, 'cart'])->name('shop.cart');

        Route::get('{page}', [PageController::class, 'show'])->name('pages.show');
        Route::get('/user/wallet', [UserWalletController::class, 'show'])->name('wallet.show');

        Route::get('quizzes/{quiz}', [QuizController::class, 'show'])->name('quizzes.show');
    });

    /**
     * Auth routes
     */
    includeRouteFiles(__DIR__.'/tenant/auth');
});
