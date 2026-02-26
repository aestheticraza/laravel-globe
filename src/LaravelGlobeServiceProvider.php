<?php

namespace Aestheticraza\LaravelGlobe;

use Illuminate\Support\ServiceProvider;

class LaravelGlobeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Merge configuration
        $this->mergeConfigFrom(
            __DIR__ . '/../config/laravelglobe.php',
            'laravelglobe'
        );

        $this->app->singleton('laravelglobe', function ($app) {
            return new \Aestheticraza\LaravelGlobe\Services\GlobeService();
        });

        // Repository Bindings
        $this->app->bind(
            \Aestheticraza\LaravelGlobe\Repositories\Contracts\CountryRepositoryInterface::class,
            function () {
                $repository = new \Aestheticraza\LaravelGlobe\Repositories\Eloquent\CountryRepository();
                return new \Aestheticraza\LaravelGlobe\Repositories\CacheDecorators\CacheCountryRepository($repository);
            }
        );

        // Register Events
        $this->app['events']->listen(
            \Aestheticraza\LaravelGlobe\Events\CountryUpdated::class,
            [\Aestheticraza\LaravelGlobe\Listeners\ClearCountryCache::class, 'handle']
        );
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Auto-publish config on first install if missing
        if ($this->app->runningInConsole() && !file_exists(config_path('laravelglobe.php'))) {
            $this->publishes([
                __DIR__ . '/../config/laravelglobe.php' => config_path('laravelglobe.php'),
            ], 'laravelglobe-config');
        } else {
            // Publish configuration file normally
            $this->publishes([
                __DIR__ . '/../config/laravelglobe.php' => config_path('laravelglobe.php'),
            ], 'laravelglobe-config');
        }

        // Load Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Load Routes natively if enabled in configuration
        if (config('laravelglobe.routes_enabled', true)) {
            $this->loadRoutesFrom(__DIR__ . '/routes.php');
        }

        // Register Observers for heavy data resolution handling natively
        \Aestheticraza\LaravelGlobe\Models\City::observe(\Aestheticraza\LaravelGlobe\Observers\CityObserver::class);

        // Publish Migrations
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../database/migrations/' => database_path('migrations'),
            ], 'laravelglobe-migrations');

            $this->commands([
                Commands\GlobeInstallCommand::class,
                Commands\GlobeRefreshCommand::class,
                Commands\GlobeStatusCommand::class,
                Commands\GlobeExportCommand::class,
                Commands\GlobeImportCommand::class,
            ]);
        }
    }
}
