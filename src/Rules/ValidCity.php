<?php

namespace Yourname\LaravelGlobe\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Yourname\LaravelGlobe\Models\City;

class ValidCity implements ValidationRule
{
    protected ?string $countryCode;

    public function __construct(?string $countryCode = null)
    {
        $this->countryCode = $countryCode;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $query = City::where('id', $value);
        if ($this->countryCode) {
            $query->whereHas('country', function ($q) {
                $q->where('iso2', $this->countryCode);
            });
        }

        if (!$query->exists()) {
            $fail('The selected city is invalid' . ($this->countryCode ? ' for this country.' : '.'));
        }
    }
}
