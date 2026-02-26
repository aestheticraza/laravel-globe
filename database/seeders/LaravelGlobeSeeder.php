<?php

namespace Aestheticraza\LaravelGlobe\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Aestheticraza\LaravelGlobe\Models\Country;

class LaravelGlobeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ensure cache is cleared
        Country::clearCache();

        $this->command->info('🌍 LaravelGlobe: Seeding started.');

        // For cross-database compatibility (ignoring foreign keys during truncation)
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        DB::table(config('laravelglobe.tables.cities'))->truncate();
        DB::table(config('laravelglobe.tables.states'))->truncate();
        DB::table(config('laravelglobe.tables.countries'))->truncate();
        DB::table(config('laravelglobe.tables.currencies'))->truncate();
        DB::table(config('laravelglobe.tables.timezones'))->truncate();

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->seedCurrencies();
        $this->seedTimezones();
        $this->seedCountries();
        $this->seedStates();
        $this->seedCitiesInChunks();

        $this->command->info('✅ LaravelGlobe: Seeding completed successfully!');
    }

    private function seedCurrencies(): void
    {
        $this->command->info('💰 Seeding Currencies...');
        $file = __DIR__ . '/../../data/currencies.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table(config('laravelglobe.tables.currencies'))->insert($chunk);
            }
        } else {
            $this->command->warn('data/currencies.json not found! Skipping.');
        }
    }

    private function seedTimezones(): void
    {
        $this->command->info('⏰ Seeding Timezones...');
        $file = __DIR__ . '/../../data/timezones.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table(config('laravelglobe.tables.timezones'))->insert($chunk);
            }
        } else {
            $this->command->warn('data/timezones.json not found! Skipping.');
        }
    }

    private function seedCountries(): void
    {
        $this->command->info('🏳️  Seeding Countries...');
        $file = __DIR__ . '/../../data/countries.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            foreach (array_chunk($data, 500) as $chunk) {
                DB::table(config('laravelglobe.tables.countries'))->insert($chunk);
            }
        } else {
            $this->command->warn('data/countries.json not found! Skipping.');
        }
    }

    private function seedStates(): void
    {
        $this->command->info('🗺️  Seeding States...');
        $file = __DIR__ . '/../../data/states.json';
        if (file_exists($file)) {
            $data = json_decode(file_get_contents($file), true);
            foreach (array_chunk($data, 1000) as $chunk) {
                DB::table(config('laravelglobe.tables.states'))->insert($chunk);
            }
        } else {
            $this->command->warn('data/states.json not found! Skipping.');
        }
    }

    private function seedCitiesInChunks(): void
    {
        $this->command->info('🏙️  Seeding Cities in Chunks...');
        $path = __DIR__ . '/../../data/cities/';
        $files = glob($path . 'cities_*.json');

        if (empty($files)) {
            $this->command->warn('No split city files found in data/cities/! Please provide cities_a.json, cities_b.json etc.');
            return;
        }

        foreach ($files as $file) {
            $cities = json_decode(file_get_contents($file), true);
            if (is_array($cities)) {
                foreach (array_chunk($cities, 1000) as $chunk) {
                    DB::table(config('laravelglobe.tables.cities'))->insert($chunk);
                }
                $this->command->info("Seeded chunk: " . basename($file));
            }
        }
    }
}
