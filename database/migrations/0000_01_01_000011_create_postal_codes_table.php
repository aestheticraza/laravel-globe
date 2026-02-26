<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = config('laravelglobe.tables.postal_codes', 'postal_codes');
        $citiesTable = config('laravelglobe.tables.cities', 'cities');
        $countriesTable = config('laravelglobe.tables.countries', 'countries');

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) use ($citiesTable, $countriesTable) {
                $table->id();
                $table->foreignId('city_id')->constrained($citiesTable)->cascadeOnDelete();
                $table->foreignId('country_id')->constrained($countriesTable)->cascadeOnDelete();
                $table->string('code');
                $table->string('area_name')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();

                $table->index(['country_id', 'code']);
                $table->index(['city_id', 'is_active']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(config('laravelglobe.tables.postal_codes', 'postal_codes'));
    }
};
