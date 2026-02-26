<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $statesTable = config('laravelglobe.tables.states', 'states');
        $timezonesTable = config('laravelglobe.tables.timezones', 'timezones');

        Schema::create('state_timezone', function (Blueprint $table) use ($statesTable, $timezonesTable) {
            $table->id();
            $table->foreignId('state_id')->constrained($statesTable)->cascadeOnDelete();
            $table->foreignId('timezone_id')->constrained($timezonesTable)->cascadeOnDelete();
            $table->boolean('is_primary')->default(false);
            $table->string('region_note')->nullable();
            $table->timestamps();

            $table->unique(['state_id', 'timezone_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('state_timezone');
    }
};
