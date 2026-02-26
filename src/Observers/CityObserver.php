<?php

namespace Yourname\LaravelGlobe\Observers;

use Yourname\LaravelGlobe\Models\City;

class CityObserver
{
    /**
     * Listen to the City saved event.
     * Automatically cascade the heavy resolution chain to a local DB column.
     *
     * @param  \Yourname\LaravelGlobe\Models\City  $city
     * @return void
     */
    public function saved(City $city)
    {
        // Prevent infinite loops if updating quietly is not natively supported
        if ($city->isDirty('resolved_timezone_id')) {
            return;
        }

        $timezone = $city->primaryTimezone();

        // Compatibility depending on Laravel version
        if (method_exists($city, 'updateQuietly')) {
            $city->updateQuietly([
                'resolved_timezone_id' => $timezone?->id,
                'timezone_resolved_at' => now()
            ]);
        } else {
            \Illuminate\Database\Eloquent\Model::withoutEvents(function () use ($city, $timezone) {
                $city->update([
                    'resolved_timezone_id' => $timezone?->id,
                    'timezone_resolved_at' => now()
                ]);
            });
        }
    }
}
