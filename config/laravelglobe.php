<?php

return [
    'tables' => [
        'prefix' => 'laravelglobe_',
        'countries' => 'countries',
        'states' => 'states',
        'cities' => 'cities',
        'currencies' => 'currencies',
        'timezones' => 'timezones',
        'postal_codes' => 'postal_codes',
        'country_currency' => 'country_currency',
        'country_timezone' => 'country_timezone',
        'state_timezone' => 'state_timezone',
        'city_timezone' => 'city_timezone',
        'city_currency' => 'city_currency',
    ],

    'features' => [
        'enable_postal_codes' => true,
        'enable_border_currencies' => true,
        'enable_geospatial' => true,
        'enable_native_geospatial' => false, // MySQL 5.7+ only
        'enable_caching' => true,
        'enable_query_logging' => false,
        'strict_mode' => true,
        'fallback_chain_enabled' => true,
    ],

    'performance' => [
        'cache_ttl' => 86400,
        'cache_store' => 'redis', // redis|file|database
        'chunk_size' => 1000,
        'nearby_default_radius' => 10,
        'max_search_results' => 50,
        'enable_query_cache' => true,
    ],

    'api' => [
        'enabled' => true,
        'prefix' => 'api/globe',
        'middleware' => ['api', 'throttle:globe'],
        'rate_limit' => 60,
        'include_counts' => true, // states_count, cities_count in response
    ],

    'advanced' => [
        'sanitize_input' => true,
        'max_export_rows' => 10000,
        'cache_warming_on_boot' => false,
        'observer_enabled' => true,
    ],
];
