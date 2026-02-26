<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $countriesTable = config('laravelglobe.tables.countries', 'countries');
        $currenciesTable = config('laravelglobe.tables.currencies', 'currencies');

        Schema::create('country_currency', function (Blueprint $table) use ($countriesTable, $currenciesTable) {
            $table->id();
            $table->foreignId('country_id')->constrained($countriesTable)->cascadeOnDelete();
            $table->foreignId('currency_id')->constrained($currenciesTable)->cascadeOnDelete();

            // Metadata
            $table->boolean('is_primary')->default(false);
            $table->boolean('is_legal_tender')->default(true);
            $table->boolean('is_historical')->default(false);
            $table->timestamp('adopted_at')->nullable();
            $table->timestamp('deprecated_at')->nullable();
            $table->decimal('exchange_rate', 20, 10)->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->unique(['country_id', 'currency_id']);
            $table->index(['country_id', 'is_primary']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_currency');
    }
};
