<?php

namespace Aestheticraza\LaravelGlobe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostalCode extends Model
{
    protected $guarded = [];

    protected $with = ['city', 'country'];

    public function getTable()
    {
        return config('laravelglobe.tables.postal_codes', 'postal_codes');
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
