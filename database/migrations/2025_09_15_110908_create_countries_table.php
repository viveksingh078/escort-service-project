<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('countries', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 100);
            $table->char('iso3', 3)->nullable();
            $table->char('numeric_code', 3)->nullable();
            $table->char('iso2', 2)->nullable();
            $table->string('phonecode')->nullable();
            $table->string('capital')->nullable();
            $table->string('currency')->nullable();
            $table->string('currency_name')->nullable();
            $table->string('currency_symbol')->nullable();
            $table->string('tld')->nullable();
            $table->string('native')->nullable();
            $table->string('region')->nullable();
            $table->mediumInteger('region_id')->unsigned()->nullable();
            $table->string('subregion')->nullable();
            $table->mediumInteger('subregion_id')->unsigned()->nullable();
            $table->string('nationality')->nullable();
            $table->text('timezones')->nullable();
            $table->text('translations')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('emoji', 191)->nullable();
            $table->string('emojiU', 191)->nullable();
            $table->timestamps();
            $table->boolean('flag')->default(1);
            $table->string('wikiDataId')->nullable()->comment('Rapid API GeoDB Cities');

            // Indexes
            $table->index('region_id', 'country_continent');
            $table->index('subregion_id', 'country_subregion');

            // Remove foreign keys for now to avoid errors
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
