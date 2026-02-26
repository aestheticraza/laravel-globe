<?php

namespace Yourname\LaravelGlobe\Commands;

use Illuminate\Console\Command;
use Yourname\LaravelGlobe\Models\Country;
use Illuminate\Support\Facades\File;

class GlobeExportCommand extends Command
{
    protected $signature = 'globe:export {--module=countries : Module to export (countries, states, cities, timezones, currencies)}';
    protected $description = 'Export Globe data to JSON inside data directory.';

    public function handle()
    {
        $module = $this->option('module');
        $this->info("Exporting {$module} to JSON...");

        $data = [];
        if ($module === 'countries') {
            $data = Country::all()->makeHidden(['created_at', 'updated_at', 'deleted_at'])->toArray();
        } else {
            $this->error('Currently only countries export is fully supported in this scaffold!');
            return;
        }

        $path = __DIR__ . "/../../data/{$module}_export.json";
        File::put($path, json_encode($data, JSON_PRETTY_PRINT));

        $this->info("Export completed: " . realpath($path));
    }
}
