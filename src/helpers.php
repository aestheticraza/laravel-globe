<?php

use Illuminate\Database\Eloquent\Collection;
use Aestheticraza\LaravelGlobe\Models\Country;

if (!function_exists('globe_countries')) {
    function globe_countries(): Collection
    {
        return Country::getActive();
    }
}

if (!function_exists('globe_phone_code')) {
    function globe_phone_code(string $iso2): ?string
    {
        return Country::where('iso2', strtoupper($iso2))->value('phone_code');
    }
}
