<?php

namespace Yourname\LaravelGlobe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GlobeInstallCommand extends Command
{
    protected $signature = 'globe:install';
    protected $description = 'Setup the LaravelGlobe package (migrations, seeders, config).';

    public function handle()
    {
        $this->info('Publishing Configuration...');
        $this->call('vendor:publish', ['--tag' => 'laravelglobe-config']);

        $this->info('Publishing Migrations and Seeders...');
        $this->call('vendor:publish', ['--tag' => 'laravelglobe-migrations']);
        $this->call('vendor:publish', ['--tag' => 'laravelglobe-seeders']);

        if ($this->confirm('Do you want to run the migrations now?')) {
            $this->call('migrate');
        }

        if ($this->confirm('Do you want to seed the Globe data? This might take a few minutes.')) {
            $this->call('db:seed', ['--class' => 'Yourname\\LaravelGlobe\\Seeders\\LaravelGlobeSeeder']);
        }

        $this->info('LaravelGlobe installed successfully!');
    }
}
