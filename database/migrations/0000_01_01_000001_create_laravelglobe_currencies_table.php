<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = config('laravelglobe.tables.currencies', 'currencies');
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('code', 10)->unique();
                $table->string('symbol', 20)->nullable();
                $table->timestamps();
                $table->softDeletes();

                $table->index('code');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(config('laravelglobe.tables.currencies', 'currencies'));
    }
};
