<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->string('family')->nullable()->after('name');
            $table->string('family_slug')->nullable()->after('family');
            $table->unsignedInteger('sort_order')->default(0)->after('badge_text');
        });
    }

    public function down(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn(['family', 'family_slug', 'sort_order']);
        });
    }
};
