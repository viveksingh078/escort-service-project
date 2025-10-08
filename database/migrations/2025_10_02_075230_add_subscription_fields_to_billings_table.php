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
        Schema::table('billings', function (Blueprint $table) {
            $table->string('subscription_type')->default('new')->after('status');
            $table->unsignedBigInteger('previous_plan_id')->nullable()->after('subscription_type');
            $table->decimal('credit_amount', 10, 2)->nullable()->after('previous_plan_id');
            $table->timestamp('starts_at')->nullable()->after('credit_amount');
            $table->timestamp('expires_at')->nullable()->after('starts_at');
            
            // Add foreign key for previous_plan_id
            $table->foreign('previous_plan_id')->references('id')->on('subscription_plans')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('billings', function (Blueprint $table) {
            $table->dropForeign(['previous_plan_id']);
            $table->dropColumn([
                'subscription_type',
                'previous_plan_id', 
                'credit_amount',
                'starts_at',
                'expires_at'
            ]);
        });
    }
};