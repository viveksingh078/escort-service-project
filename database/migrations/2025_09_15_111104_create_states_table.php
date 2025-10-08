<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('states', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 255);
            $table->mediumInteger('country_id')->unsigned();
            $table->char('country_code', 2);
            $table->string('fips_code', 255)->nullable();
            $table->string('iso2', 255)->nullable();
            $table->string('iso3166_2', 10)->nullable();
            $table->string('type', 191)->nullable();
            $table->integer('level')->nullable();
            $table->unsignedInteger('parent_id')->nullable();
            $table->string('native', 255)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('timezone', 255)->nullable()->comment('IANA timezone identifier (e.g., America/New_York)');
            $table->timestamps();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');

            // Indexes & foreign key
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->index('country_id', 'country_region');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('states');
    }
};
