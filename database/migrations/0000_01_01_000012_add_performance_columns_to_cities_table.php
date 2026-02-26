<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $citiesTable = config('laravelglobe.tables.cities', 'cities');

        if (Schema::hasTable($citiesTable)) {
            Schema::table($citiesTable, function (Blueprint $table) {
                $table->foreignId('resolved_timezone_id')
                    ->nullable()
                    ->constrained(config('laravelglobe.tables.timezones', 'timezones'))
                    ->nullOnDelete();
                $table->timestamp('timezone_resolved_at')->nullable();
                $table->index(['resolved_timezone_id', 'is_active']); // Covering index
            });
        }
    }

    public function down(): void
    {
        $citiesTable = config('laravelglobe.tables.cities', 'cities');

        if (Schema::hasTable($citiesTable)) {
            Schema::table($citiesTable, function (Blueprint $table) {
                $table->dropForeign(['resolved_timezone_id']);
                $table->dropIndex(['resolved_timezone_id', 'is_active']);
                $table->dropColumn(['resolved_timezone_id', 'timezone_resolved_at']);
            });
        }
    }
};
