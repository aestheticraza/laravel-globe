<?php

namespace Yourname\LaravelGlobe\Traits;

use Yourname\LaravelGlobe\Models\Country;
use Yourname\LaravelGlobe\Models\State;
use Yourname\LaravelGlobe\Models\City;

trait HasLocation
{
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function state()
    {
        return $this->belongsTo(State::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function getFullAddressAttribute()
    {
        return implode(', ', array_filter([
            $this->address_line_1 ?? null,
            $this->city?->name,
            $this->state?->name,
            $this->country?->name,
            $this->postal_code ?? null,
        ]));
    }

    public function scopeWithinRadius($query, $lat, $lng, $radiusKm)
    {
        return $query->whereHas('city', function ($q) use ($lat, $lng, $radiusKm) {
            $q->nearby($lat, $lng, $radiusKm);
        });
    }

    public function scopeInCountry($query, string $iso2)
    {
        return $query->whereHas('country', fn($q) => $q->where('iso2', $iso2));
    }

    public function scopeInTimezone($query, string $zoneName)
    {
        return $query->whereHas('city.timezones', fn($q) => $q->where('zone_name', $zoneName));
    }

    public function initializeHasLocation()
    {
        $this->casts = array_merge($this->casts ?? [], [
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ]);
    }
}
