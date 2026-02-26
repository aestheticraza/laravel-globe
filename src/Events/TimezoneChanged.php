<?php

namespace Aestheticraza\LaravelGlobe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Aestheticraza\LaravelGlobe\Models\Timezone;

class TimezoneChanged
{
    use Dispatchable, SerializesModels;

    public Timezone $timezone;

    public function __construct(Timezone $timezone)
    {
        $this->timezone = $timezone;
    }
}
