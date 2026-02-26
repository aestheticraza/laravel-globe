<?php

namespace Aestheticraza\LaravelGlobe\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;
use Aestheticraza\LaravelGlobe\Models\Country;

class ValidCountry implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!Country::where('iso2', $value)->exists()) {
            $fail('The selected country is invalid.');
        }
    }
}
