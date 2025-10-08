<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (Schema::hasTable('billings')) {
            Schema::table('billings', function (Blueprint $table) {
                if (!Schema::hasColumn('billings', 'fan_id')) {
                    $table->unsignedBigInteger('fan_id')->nullable()->after('id');
                }
                if (!Schema::hasColumn('billings', 'plan_id')) {
                    $table->unsignedBigInteger('plan_id')->nullable()->after('escort_id');
                }
                if (!Schema::hasColumn('billings', 'amount')) {
                    $table->decimal('amount', 10, 2)->nullable()->after('country');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('billings')) {
            Schema::table('billings', function (Blueprint $table) {
                if (Schema::hasColumn('billings', 'fan_id')) {
                    $table->dropColumn('fan_id');
                }
                if (Schema::hasColumn('billings', 'plan_id')) {
                    $table->dropColumn('plan_id');
                }
                if (Schema::hasColumn('billings', 'amount')) {
                    $table->dropColumn('amount');
                }
            });
        }
    }
};


