<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $citiesTable = config('laravelglobe.tables.cities', 'cities');
        $currenciesTable = config('laravelglobe.tables.currencies', 'currencies');

        Schema::create('city_currency', function (Blueprint $table) use ($citiesTable, $currenciesTable) {
            $table->id();
            $table->foreignId('city_id')->constrained($citiesTable)->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained($currenciesTable)->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_border_accepted')->default(false);
            $table->decimal('exchange_rate_margin', 5, 2)->nullable();
            $table->timestamps();

            $table->unique(['city_id', 'currency_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('city_currency');
    }
};
