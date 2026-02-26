<?php

namespace Aestheticraza\LaravelGlobe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class State extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    protected $with = ['country'];

    public function getTable()
    {
        return config('laravelglobe.tables.states', 'states');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function timezones(): BelongsToMany
    {
        return $this->belongsToMany(Timezone::class, 'state_timezone')
            ->withPivot(['is_primary', 'region_note'])
            ->withTimestamps();
    }

    public function primaryTimezone()
    {
        return $this->timezones()->wherePivot('is_primary', true)->first()
            ?? $this->country->primaryTimezone();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByCountry($query, string $countryCode)
    {
        return $query->whereHas('country', fn($q) => current($q)->where('iso2', $countryCode));
    }
}
