<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Enable all plan families so they appear in the onboarding plan selector.
        // Previously only smart_tv (3/6/12m) and sup (12m only) were enabled;
        // max and trex were all disabled, causing them to disappear from the UI.
        DB::table('plans')->update(['is_enabled' => true]);
    }

    public function down(): void
    {
        // Restore the previous state: only smart_tv (all) and sup (12m) enabled.
        DB::table('plans')->update(['is_enabled' => false]);

        DB::table('plans')
            ->where('family_slug', 'smart_tv')
            ->update(['is_enabled' => true]);

        DB::table('plans')
            ->where('family_slug', 'sup')
            ->where('duration_months', 12)
            ->update(['is_enabled' => true]);
    }
};
