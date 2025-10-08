<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCountryFlagsTable extends Migration
{
    public function up(): void
    {
        Schema::create('country_flags', function (Blueprint $table) {
            $table->id();

            $table->unsignedMediumInteger('country_id')->nullable(); // same type as countries.id
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('set null');


            $table->string('name', 100);
            $table->string('flag_path')->nullable(); // Path to flag image
            $table->timestamps();


        });
    }

    public function down(): void
    {
        Schema::dropIfExists('country_flags');
    }
}