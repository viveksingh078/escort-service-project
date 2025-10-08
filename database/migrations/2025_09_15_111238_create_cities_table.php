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
        Schema::create('cities', function (Blueprint $table) {
            $table->mediumIncrements('id');
            $table->string('name', 255);
            $table->mediumInteger('state_id')->unsigned();
            $table->string('state_code', 255);
            $table->mediumInteger('country_id')->unsigned();
            $table->char('country_code', 2);
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->string('timezone', 255)->nullable()->comment('IANA timezone identifier (e.g., America/New_York)');
            $table->timestamp('created_at')->default('2014-01-01 12:01:01');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->tinyInteger('flag')->default(1);
            $table->string('wikiDataId', 255)->nullable()->comment('Rapid API GeoDB Cities');

            // Indexes & foreign keys
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->index('state_id', 'cities_state_idx');
            $table->index('country_id', 'cities_country_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
