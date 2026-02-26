<?php

namespace Aestheticraza\LaravelGlobe\Listeners;

use Aestheticraza\LaravelGlobe\Events\CountryUpdated;
use Aestheticraza\LaravelGlobe\Models\Country;

class ClearCountryCache
{
    public function handle(CountryUpdated $event)
    {
        Country::clearCache();
    }
}
