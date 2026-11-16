<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->string('slug')->nullable()->unique()->after('name');
        });

        $shops = DB::table('shops')->select('id', 'name')->get();
        foreach ($shops as $shop) {
            $baseSlug = Str::slug($shop->name) ?: 'shop';
            $slug = $baseSlug;
            $counter = 2;

            while (DB::table('shops')->where('slug', $slug)->where('id', '!=', $shop->id)->exists()) {
                $slug = "{$baseSlug}-{$counter}";
                $counter++;
            }

            DB::table('shops')->where('id', $shop->id)->update(['slug' => $slug]);
        }
    }

    public function down(): void
    {
        Schema::table('shops', function (Blueprint $table) {
            $table->dropUnique(['slug']);
            $table->dropColumn('slug');
        });
    }
};
