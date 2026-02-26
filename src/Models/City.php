<?php

namespace Yourname\LaravelGlobe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class City extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'state_id',
        'country_id',
        'name',
        'latitude',
        'longitude',
        'is_active',
        'resolved_timezone_id',
        'timezone_resolved_at'
    ];

    protected $with = ['state', 'country'];

    public function getTable()
    {
        return config('laravelglobe.tables.cities', 'cities');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function postalCodes()
    {
        return $this->hasMany(PostalCode::class);
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'city_currency')
            ->withPivot(['is_primary', 'is_border_accepted', 'exchange_rate_margin'])
            ->withTimestamps();
    }

    public function timezones(): BelongsToMany
    {
        return $this->belongsToMany(Timezone::class, 'city_timezone')
            ->withPivot(['is_primary', 'observes_dst']);
    }

    public function primaryTimezone()
    {
        return $this->timezones()->wherePivot('is_primary', true)->first()
            ?? $this->state->primaryTimezone()
            ?? $this->country->primaryTimezone();
    }

    public function scopeSameTimezone($query, ?City $referenceCity = null)
    {
        $referenceCity ??= $this;
        return $query->where('resolved_timezone_id', $referenceCity->resolved_timezone_id);
    }

    public function scopeNearby($query, $lat, $lng, $radiusKm = 10)
    {
        return \Yourname\LaravelGlobe\Services\GeospatialEngine::scopeNearby($query, $lat, $lng, $radiusKm);
    }
}
