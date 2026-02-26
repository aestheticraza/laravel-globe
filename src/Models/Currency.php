<?php

namespace Yourname\LaravelGlobe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Currency extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function getTable()
    {
        return config('laravelglobe.tables.currencies', 'currencies');
    }
}
