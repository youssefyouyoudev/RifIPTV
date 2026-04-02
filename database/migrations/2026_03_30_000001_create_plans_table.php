<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price_mad', 10, 2);
            $table->json('features');
            $table->boolean('is_featured')->default(false);
            $table->string('badge_text')->nullable();
            $table->timestamps();

            $table->index('is_featured');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
