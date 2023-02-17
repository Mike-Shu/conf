<?php

namespace App\Http\Livewire;

use App\Settings\TenantSettings;
use Livewire\Component;

class Logo extends Component
{
    public function render(TenantSettings $tenantSettings)
    {
        return view('livewire.logo', ['logo' => $tenantSettings->logo]);
    }
}
