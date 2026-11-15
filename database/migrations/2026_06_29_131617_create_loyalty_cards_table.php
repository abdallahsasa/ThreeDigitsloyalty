<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('loyalty_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->cascadeOnDelete();
            $table->foreignId('shop_id')->constrained('shops')->cascadeOnDelete();
            $table->foreignId('campaign_id')->constrained('loyalty_campaigns')->cascadeOnDelete();
            $table->string('token', 64)->unique(); // secure token
            $table->integer('current_stamps_count')->default(0);
            $table->string('reward_status')->default('not_ready'); // not_ready, ready, redeemed, expired
            $table->timestamp('last_activity_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_cards');
    }
};