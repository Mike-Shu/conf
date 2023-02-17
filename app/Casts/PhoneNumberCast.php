<?php

namespace App\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class PhoneNumberCast implements CastsAttributes
{
    /**
     * @inheritDoc
     */
    public function get($model, $key, $value, $attributes): string
    {
        return trim($value);
    }

    /**
     * @inheritDoc
     */
    public function set($model, $key, $value, $attributes): string
    {
        return getFullPhoneNumber($value);
    }
}
