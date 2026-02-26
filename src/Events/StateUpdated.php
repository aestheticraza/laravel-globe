<?php

namespace Yourname\LaravelGlobe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Yourname\LaravelGlobe\Models\State;

class StateUpdated
{
    use Dispatchable, SerializesModels;

    public State $state;

    public function __construct(State $state)
    {
        $this->state = $state;
    }
}
