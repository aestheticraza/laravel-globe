<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = config('laravelglobe.tables.states', 'states');
        $countriesTable = config('laravelglobe.tables.countries', 'countries');

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) use ($countriesTable) {
                $table->id();
                $table->foreignId('country_id')->constrained($countriesTable)->cascadeOnDelete();
                $table->string('name');
                $table->string('state_code')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['country_id', 'is_active']);
                $table->index('state_code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(config('laravelglobe.tables.states', 'states'));
    }
};
