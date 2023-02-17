<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;

class UserWalletController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @return Application|Factory|View
     */
    public function show(): Application|Factory|View
    {
        return view('pages.user-wallet-log');
    }
}
