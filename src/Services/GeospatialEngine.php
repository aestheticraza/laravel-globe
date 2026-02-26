<?php

namespace Yourname\LaravelGlobe\Services;

use Illuminate\Support\Facades\DB;

class GeospatialEngine
{
    public static function scopeNearby($query, $lat, $lng, $radiusKm = 10)
    {
        $version = self::detectMysqlVersion();

        // MySQL 5.7+ with GEOMETRY support
        if ($version >= 5.7 && config('laravelglobe.features.enable_geospatial', true)) {
            return self::nativeSphereDistance($query, $lat, $lng, $radiusKm);
        }

        // Fallback: Haversine formula (broad compatibility)
        return self::haversineDistance($query, $lat, $lng, $radiusKm);
    }

    private static function nativeSphereDistance($query, $lat, $lng, $radiusKm)
    {
        return $query->selectRaw("
            *,
            ST_Distance_Sphere(
                POINT(longitude, latitude),
                POINT(?, ?)
            ) / 1000 AS distance
        ", [$lng, $lat])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

    private static function haversineDistance($query, $lat, $lng, $radiusKm)
    {
        return $query->selectRaw("
            *,
            (6371 * acos(
                cos(radians(?)) * cos(radians(latitude)) * 
                cos(radians(longitude) - radians(?)) + 
                sin(radians(?)) * sin(radians(latitude))
            )) AS distance
        ", [$lat, $lng, $lat])
            ->having('distance', '<=', $radiusKm)
            ->orderBy('distance');
    }

    private static function detectMysqlVersion(): float
    {
        try {
            $versionString = DB::select('select version() as version')[0]->version ?? '0.0';
            // Extract major.minor (e.g., "5.7.33" -> 5.7, "8.0.23" -> 8.0)
            preg_match('/^(\d+\.\d+)/', $versionString, $matches);
            return (float) ($matches[1] ?? '0.0');
        } catch (\Exception $e) {
            return 0.0;
        }
    }
}
