<?php

namespace Yourname\LaravelGlobe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Yourname\LaravelGlobe\Models\Country;

class CountryUpdated
{
    use Dispatchable, SerializesModels;

    public Country $country;

    public function __construct(Country $country)
    {
        $this->country = $country;
    }
}
