<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        $tableName = config('laravelglobe.tables.cities', 'cities');
        $statesTable = config('laravelglobe.tables.states', 'states');
        $countriesTable = config('laravelglobe.tables.countries', 'countries');

        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) use ($statesTable, $countriesTable) {
                $table->id();
                $table->foreignId('state_id')->constrained($statesTable)->cascadeOnDelete();
                $table->foreignId('country_id')->constrained($countriesTable)->cascadeOnDelete();
                $table->string('name');
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();

                $table->index(['state_id', 'is_active']);
                $table->index(['country_id', 'is_active']);
                $table->index('name');
                if (DB::connection()->getDriverName() === 'mysql') {
                    $table->fullText('name');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(config('laravelglobe.tables.cities', 'cities'));
    }
};
