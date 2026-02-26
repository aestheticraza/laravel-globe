<?php

namespace Aestheticraza\LaravelGlobe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'iso2',
        'iso3',
        'numeric_code',
        'phone_code',
        'capital',
        'tld',
        'native',
        'region',
        'subregion',
        'latitude',
        'longitude',
        'emoji',
        'emojiU',
        'is_active',
        'primary_currency_id'
    ];

    public function getTable()
    {
        return config('laravelglobe.tables.countries', 'countries');
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }

    public function timezones(): BelongsToMany
    {
        return $this->belongsToMany(Timezone::class, 'country_timezone')
            ->withPivot('is_primary')
            ->withTimestamps();
    }

    public function primaryTimezone()
    {
        return $this->timezones()->wherePivot('is_primary', true)->first();
    }

    public function currencies(): BelongsToMany
    {
        return $this->belongsToMany(Currency::class, 'country_currency')
            ->withPivot(['is_primary', 'is_legal_tender', 'exchange_rate'])
            ->withTimestamps();
    }

    public function primaryCurrency(): BelongsTo
    {
        return $this->belongsTo(Currency::class, 'primary_currency_id');
    }

    public function activeCurrencies()
    {
        return $this->currencies()
            ->wherePivot('is_legal_tender', true)
            ->wherePivotNull('deprecated_at')
            ->get();
    }

    public static function getActive()
    {
        return Cache::remember('laravelglobe:countries', 86400, function () {
            return self::where('is_active', true)->get();
        });
    }

    public static function clearCache()
    {
        Cache::forget('laravelglobe:countries');
    }
}
