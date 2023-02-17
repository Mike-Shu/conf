<?php

namespace App\Http\Livewire;

use App\Settings\TenantSettings;
use Livewire\Component;
use RyanChandler\FilamentNavigation\Facades\FilamentNavigation;

class ShowMainMenu extends Component
{
    public function render(TenantSettings $tenantSettings)
    {
        $menu = $tenantSettings->menu ? FilamentNavigation::get($tenantSettings->menu) : null;
        return view('livewire.show-main-menu', ['menu' => $menu]);
    }
}
