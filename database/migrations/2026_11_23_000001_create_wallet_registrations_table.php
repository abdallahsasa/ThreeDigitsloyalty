<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('loyalty_cards', function (Blueprint $table) {
            $table->string('wallet_message_title')->nullable()->after('last_activity_date');
            $table->text('wallet_message')->nullable()->after('wallet_message_title');
            $table->timestamp('wallet_message_updated_at')->nullable()->after('wallet_message');
        });

        Schema::create('wallet_registrations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('loyalty_card_id')->constrained('loyalty_cards')->cascadeOnDelete();
            $table->string('device_library_identifier');
            $table->string('pass_type_identifier');
            $table->string('push_token');
            $table->string('serial_number');
            $table->timestamps();

            $table->unique(['device_library_identifier', 'serial_number'], 'wallet_reg_device_serial_unique');
            $table->index(['pass_type_identifier', 'serial_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_registrations');

        Schema::table('loyalty_cards', function (Blueprint $table) {
            $table->dropColumn(['wallet_message_title', 'wallet_message', 'wallet_message_updated_at']);
        });
    }
};
