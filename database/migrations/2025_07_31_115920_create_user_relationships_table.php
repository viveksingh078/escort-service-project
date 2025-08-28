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
        Schema::create('user_relationships', function (Blueprint $table) {
            $table->id();
            
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('related_user_id');

            // Type of relationship
            $table->enum('relationship_type', ['friend', 'favorite', 'blocked']);

            // Optional fields
            $table->timestamp('relationship_since')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            // Foreign keys
            $table->foreign('user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('related_user_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            // Prevent duplicate entries per type
            $table->unique(['user_id', 'related_user_id', 'relationship_type'], 'unique_user_relationship');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_relationships');
    }
};
