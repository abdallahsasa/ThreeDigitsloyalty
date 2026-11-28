<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('card_bg_color', 20)->nullable()->after('logo');
            $table->string('card_accent_color', 20)->nullable()->after('card_bg_color');
            $table->string('card_text_color', 20)->nullable()->after('card_accent_color');
        });
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropColumn(['card_bg_color', 'card_accent_color', 'card_text_color']);
        });
    }
};
