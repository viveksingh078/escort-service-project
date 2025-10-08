<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('escort_media', function (Blueprint $table) {
            $table->id();

            // If media belongs to a specific escort/user
            $table->foreignId('escort_id')->constrained('users')->onDelete('cascade');

            // Media details
            $table->string('file_path');     
            $table->enum('media_type', ['photo', 'video']); 
            $table->string('thumbnail_path')->nullable();  
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            // Status
            $table->boolean('is_public')->default(true); 
            $table->boolean('is_approved')->default(false);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('escort_media');
    }
};
