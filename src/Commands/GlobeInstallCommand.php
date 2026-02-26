<?php

namespace Aestheticraza\LaravelGlobe\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GlobeInstallCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'globe:install {--force : Force the operation to run without prompts.}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automated setup: Config publish, Native Migrations, and Chunked Data Seeding.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🌍 Preparing LaravelGlobe installation...');

        $this->warn('Publishing package configurations and migrations...');
        $this->call('vendor:publish', [
            '--provider' => "Aestheticraza\\LaravelGlobe\\LaravelGlobeServiceProvider",
            '--force' => true
        ]);

        if ($this->option('force') || $this->confirm('Run geographical database migrations now?', true)) {
            $this->info('Running table migrations...');
            $this->call('migrate');
        }

        if ($this->option('force') || $this->confirm('Do you want to run the core globe seeder? (Will insert 150k+ records chunked)', true)) {
            $this->warn('Seeding the entire planet. This will take a moment ⏳');
            $this->call('db:seed', [
                '--class' => "Aestheticraza\\LaravelGlobe\\Database\\Seeders\\LaravelGlobeSeeder"
            ]);
        }

        $this->newLine();
        $this->info('✅ LaravelGlobe Installed Successfully! The system is fully armed.');

        return self::SUCCESS;
    }
}
