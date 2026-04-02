<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedInteger('duration_months')->default(1)->after('price_mad');
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->string('preferred_payment_method')->nullable()->after('phone');
            $table->string('preferred_bank')->nullable()->after('preferred_payment_method');
            $table->string('onboarding_status')->default('new')->after('preferred_bank');
            $table->timestamp('support_started_at')->nullable()->after('last_contacted_at');
            $table->timestamp('setup_tutorial_sent_at')->nullable()->after('support_started_at');
            $table->timestamp('credentials_sent_at')->nullable()->after('setup_tutorial_sent_at');
            $table->timestamp('completed_at')->nullable()->after('credentials_sent_at');
            $table->string('iptv_username')->nullable()->after('completed_at');
            $table->string('iptv_password')->nullable()->after('iptv_username');
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->change();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->string('provider')->nullable()->after('payment_method');
            $table->string('bank_name')->nullable()->after('provider');
            $table->foreignId('assigned_admin_id')->nullable()->after('client_id')->constrained('users')->nullOnDelete();
            $table->string('proof_path')->nullable()->after('reference');
            $table->timestamp('verified_at')->nullable()->after('paid_at');
        });
    }

    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('assigned_admin_id');
            $table->dropColumn(['provider', 'bank_name', 'proof_path', 'verified_at']);
        });

        Schema::table('subscriptions', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable(false)->change();
        });

        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn([
                'preferred_payment_method',
                'preferred_bank',
                'onboarding_status',
                'support_started_at',
                'setup_tutorial_sent_at',
                'credentials_sent_at',
                'completed_at',
                'iptv_username',
                'iptv_password',
            ]);
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropColumn('duration_months');
        });
    }
};
