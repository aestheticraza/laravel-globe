<?php

namespace Aestheticraza\LaravelGlobe\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Timezone extends Model
{
    use SoftDeletes;
    protected $guarded = [];

    public function getTable()
    {
        return config('laravelglobe.tables.timezones', 'timezones');
    }
}
