<?php

namespace Aestheticraza\LaravelGlobe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Aestheticraza\LaravelGlobe\Models\City;

class CityCreated
{
    use Dispatchable, SerializesModels;

    public City $city;

    public function __construct(City $city)
    {
        $this->city = $city;
    }
}
