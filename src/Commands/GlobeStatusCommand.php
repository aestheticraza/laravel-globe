<?php

namespace Aestheticraza\LaravelGlobe\Commands;

use Illuminate\Console\Command;
use Aestheticraza\LaravelGlobe\Models\Country;
use Aestheticraza\LaravelGlobe\Models\State;
use Aestheticraza\LaravelGlobe\Models\City;
use Aestheticraza\LaravelGlobe\Models\Currency;
use Aestheticraza\LaravelGlobe\Models\Timezone;
use Illuminate\Support\Facades\Schema;

class GlobeStatusCommand extends Command
{
    protected $signature = 'globe:status';
    protected $description = 'Check the status and counts of LaravelGlobe data.';

    public function handle()
    {
        if (!Schema::hasTable(config('laravelglobe.tables.countries'))) {
            $this->error('LaravelGlobe tables missing. Run: php artisan globe:install');
            return;
        }

        $this->table(
            ['Entity', 'Count'],
            [
                ['Countries', Country::count()],
                ['States', State::count()],
                ['Cities', City::count()],
                ['Currencies', Currency::count()],
                ['Timezones', Timezone::count()],
            ]
        );

        $this->info('Globe Data Status Check Complete.');
    }
}
