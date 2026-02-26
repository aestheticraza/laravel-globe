<?php

namespace Aestheticraza\LaravelGlobe\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Aestheticraza\LaravelGlobe\Models\Currency;

class CurrencyDeprecated
{
    use Dispatchable, SerializesModels;

    public Currency $currency;

    public function __construct(Currency $currency)
    {
        $this->currency = $currency;
    }
}
