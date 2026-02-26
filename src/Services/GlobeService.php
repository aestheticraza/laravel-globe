<?php

namespace Yourname\LaravelGlobe\Services;

use Yourname\LaravelGlobe\Models\Country;
use Yourname\LaravelGlobe\Models\City;
use Illuminate\Database\Eloquent\Collection;

class GlobeService
{
    public function getCountryByPhone(string $phone): ?Country
    {
        $code = preg_replace('/[^+0-9]/', '', $phone);
        if (str_starts_with($code, '+')) {
            $code = substr($code, 1);
        }

        return Country::where('phone_code', $code)->first();
    }

    public function searchCities(string $term): Collection
    {
        return City::where('name', 'like', "%{$term}%")
            ->with(['state', 'country'])
            ->limit(20)
            ->get();
    }
}
