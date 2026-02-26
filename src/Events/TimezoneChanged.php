<?php

namespace Yourname\LaravelGlobe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Yourname\LaravelGlobe\Models\Timezone;

class TimezoneChanged
{
    use Dispatchable, SerializesModels;

    public Timezone $timezone;

    public function __construct(Timezone $timezone)
    {
        $this->timezone = $timezone;
    }
}
