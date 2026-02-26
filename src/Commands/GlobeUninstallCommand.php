<?php

namespace Aestheticraza\LaravelGlobe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\File;

class GlobeUninstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'globe:uninstall {--force : Force the operation to run without prompts.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Completely remove LaravelGlobe published assets and database tables.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->warn('⚠️  WARNING: This action will permanently delete all geographic data, tables, and configurations related to LaravelGlobe.');

        if (!$this->option('force') && !$this->confirm('Are you absolutely sure you want to completely uninstall LaravelGlobe?', false)) {
            $this->info('Uninstall aborted.');
            return self::SUCCESS;
        }

        $this->info('🗑️  Starting Uninstallation...');

        // 1. Drop Tables safely bypassing FK checks
        $this->info('Dropping Database Tables...');
        Schema::disableForeignKeyConstraints();

        $tables = [
            'postal_codes',
            'city_currency',
            'state_timezone',
            'city_timezone',
            'country_currency',
            'country_timezone',
            config('laravelglobe.tables.cities', 'cities'),
            config('laravelglobe.tables.states', 'states'),
            config('laravelglobe.tables.countries', 'countries'),
            config('laravelglobe.tables.currencies', 'currencies'),
            config('laravelglobe.tables.timezones', 'timezones'),
        ];

        foreach ($tables as $table) {
            Schema::dropIfExists($table);
            $this->line("Dropped: {$table}");
        }

        Schema::enableForeignKeyConstraints();

        // 2. Clear Migration History
        $this->info('Cleaning up Migration History...');
        DB::table('migrations')
            ->where('migration', 'like', '%laravelglobe%')
            ->orWhere('migration', 'like', '%country_timezone%')
            ->orWhere('migration', 'like', '%country_currency%')
            ->orWhere('migration', 'like', '%city_timezone%')
            ->orWhere('migration', 'like', '%state_timezone%')
            ->orWhere('migration', 'like', '%city_currency%')
            ->orWhere('migration', 'like', '%postal_codes%')
            ->orWhere('migration', 'like', '%add_performance_columns_to_cities_table%')
            ->delete();

        // 3. Remove Published Config
        $this->info('Removing Published Configurations...');
        if (File::exists(config_path('laravelglobe.php'))) {
            File::delete(config_path('laravelglobe.php'));
            $this->line('Deleted: config/laravelglobe.php');
        }

        // 4. Remove Published Migrations
        $this->info('Removing Published Migrations...');
        $migrationFiles = File::glob(database_path('migrations/*laravelglobe*.php'));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*create_country_timezone_table.php')));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*create_country_currency_table.php')));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*create_city_timezone_table.php')));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*create_state_timezone_table.php')));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*create_city_currency_table.php')));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*create_postal_codes_table.php')));
        $migrationFiles = array_merge($migrationFiles, File::glob(database_path('migrations/*add_performance_columns_to_cities_table.php')));

        foreach ($migrationFiles as $file) {
            File::delete($file);
            $this->line('Deleted Migration: ' . basename($file));
        }

        $this->newLine();
        $this->info('✅ LaravelGlobe has been completely uninstalled and wiped from your project.');
        $this->comment('You can now safely remove the composer package using: composer remove aestheticraza/laravelglobe');

        return self::SUCCESS;
    }
}
