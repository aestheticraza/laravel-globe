<?php

namespace Yourname\LaravelGlobe\Listeners;

use Yourname\LaravelGlobe\Events\CountryUpdated;
use Yourname\LaravelGlobe\Models\Country;

class ClearCountryCache
{
    public function handle(CountryUpdated $event)
    {
        Country::clearCache();
    }
}
