<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        $tableName = config('laravelglobe.tables.countries', 'countries');
        if (!Schema::hasTable($tableName)) {
            Schema::create($tableName, function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('iso2', 2)->nullable();
                $table->string('iso3', 3)->nullable();
                $table->string('numeric_code', 3)->nullable();
                $table->string('phone_code')->nullable();
                $table->string('capital')->nullable();
                $table->foreignId('primary_currency_id')->nullable()->constrained(config('laravelglobe.tables.currencies', 'currencies'))->nullOnDelete();
                $table->string('tld')->nullable();
                $table->string('native')->nullable();
                $table->string('region')->nullable();
                $table->string('subregion')->nullable();
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->string('emoji')->nullable();
                $table->string('emojiU')->nullable();
                $table->boolean('is_active')->default(true);
                $table->timestamps();
                $table->softDeletes();

                $table->index('iso2');
                $table->index('phone_code');
                $table->index(['latitude', 'longitude']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists(config('laravelglobe.tables.countries', 'countries'));
    }
};
