<?php

namespace Aestheticraza\LaravelGlobe\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Aestheticraza\LaravelGlobe\Models\Country;
use Aestheticraza\LaravelGlobe\Repositories\Contracts\CountryRepositoryInterface;

class CountryRepository implements CountryRepositoryInterface
{
    public function findByIso(string $iso2): ?Country
    {
        return Country::where('iso2', $iso2)->first();
    }

    public function getActive(): Collection
    {
        return Country::where('is_active', true)->get();
    }

    public function search(string $term): Collection
    {
        return Country::where('name', 'like', "%{$term}%")
            ->orWhere('iso2', $term)
            ->orWhere('phone_code', $term)
            ->get();
    }

    public function getActiveWithStats(): Collection
    {
        return Country::where('is_active', true)
            ->withCount(['states', 'cities'])
            ->get();
    }
}
