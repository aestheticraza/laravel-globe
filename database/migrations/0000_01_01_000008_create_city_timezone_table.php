<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $citiesTable = config('laravelglobe.tables.cities', 'cities');
        $timezonesTable = config('laravelglobe.tables.timezones', 'timezones');

        Schema::create('city_timezone', function (Blueprint $table) use ($citiesTable, $timezonesTable) {
            $table->id();
            $table->foreignId('city_id')->constrained($citiesTable)->cascadeOnDelete();
            $table->foreignId('timezone_id')->constrained($timezonesTable)->cascadeOnDelete();

            $table->boolean('is_primary')->default(false);
            $table->boolean('observes_dst')->default(true);
            $table->year('dst_starts')->nullable();
            $table->year('dst_ends')->nullable();

            $table->timestamps();
            $table->unique(['city_id', 'timezone_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city_timezone');
    }
};
