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
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('shop_id')->nullable()->after('id')->constrained('shops')->nullOnDelete();
            $table->foreignId('branch_id')->nullable()->after('shop_id')->constrained('branches')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['shop_id']);
            $table->dropForeign(['branch_id']);
            $table->dropColumn(['shop_id', 'branch_id']);
        });
    }
};
