<?php

namespace Yourname\LaravelGlobe\Commands;

use Illuminate\Console\Command;

class GlobeRefreshCommand extends Command
{
    protected $signature = 'globe:refresh';
    protected $description = 'Refresh Globe database data (truncate and re-seed).';

    public function handle()
    {
        if ($this->confirm('This will wipe all your Countries, States, Cities data and re-seed it. Continue?')) {
            $this->info('Re-seeding Globe data...');
            $this->call('db:seed', ['--class' => 'Yourname\\LaravelGlobe\\Seeders\\LaravelGlobeSeeder']);
            $this->info('Data Refreshed Successfully!');
        }
    }
}
