<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $countriesTable = config('laravelglobe.tables.countries', 'countries');
        $timezonesTable = config('laravelglobe.tables.timezones', 'timezones');
        $tableName = 'country_timezone';

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) use ($countriesTable, $timezonesTable) {
                $table->id();
                $table->foreignId('country_id')->constrained($countriesTable)->cascadeOnDelete();
                $table->foreignId('timezone_id')->constrained($timezonesTable)->cascadeOnDelete();
                $table->boolean('is_primary')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('country_timezone');
    }
};
