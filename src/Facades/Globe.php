<?php

namespace Aestheticraza\LaravelGlobe\Facades;

use Illuminate\Support\Facades\Facade;

class Globe extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'laravelglobe';
    }
}
