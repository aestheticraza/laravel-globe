<?php

namespace Aestheticraza\LaravelGlobe\Utils;

use Aestheticraza\LaravelGlobe\Models\Country;

class PhoneValidator
{
    public function validate(string $phone, string $countryCode): bool
    {
        $country = Country::where('iso2', $countryCode)->first();
        if (!$country || empty($country->phone_code)) {
            return false;
        }

        $code = preg_replace('/[^+0-9]/', '', $phone);
        if (str_starts_with($code, '+')) {
            $code = substr($code, 1);
        }

        return str_starts_with($code, $country->phone_code);
    }

    public function format(string $phone, string $countryCode): string
    {
        $country = Country::where('iso2', $countryCode)->first();
        if (!$country || empty($country->phone_code)) {
            return $phone;
        }

        $clean = preg_replace('/[^0-9]/', '', $phone);
        if (!str_starts_with($clean, $country->phone_code)) {
            $clean = $country->phone_code . ltrim($clean, '0');
        }

        return '+' . $clean;
    }

    public function getCountryFromPhone(string $phone): ?Country
    {
        $code = preg_replace('/[^+0-9]/', '', $phone);
        if (str_starts_with($code, '+')) {
            $code = substr($code, 1);
        }

        // Search backward length starting from largest standard phone code (3 to 1)
        for ($i = 3; $i >= 1; $i--) {
            $prefix = substr($code, 0, $i);
            $country = Country::where('phone_code', $prefix)->first();
            if ($country) {
                return $country;
            }
        }

        return null;
    }
}
