<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->boolean('is_enabled')->default(true)->after('is_featured');
            $table->index('is_enabled');
        });

        DB::table('plans')->update(['is_enabled' => false]);

        DB::table('plans')
            ->where('family_slug', 'sup')
            ->where('duration_months', 12)
            ->update(['is_enabled' => true]);
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropIndex(['is_enabled']);
            $table->dropColumn('is_enabled');
        });
    }
};
