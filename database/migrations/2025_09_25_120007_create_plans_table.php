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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->index();
            $table->enum('billing_cycle', ['monthly', 'yearly'])->default('monthly')->index();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_default')->default(false)->index();
            $table->integer('max_users')->default(1);
            $table->integer('max_projects')->default(5);
            $table->string('features')->nullable();
            $table->string('currency', 3)->default('USD')->index();
            $table->integer('duration_days')->default(30);
            $table->timestamps();

            // Composite index for active/default plans by currency
            $table->index(['is_active', 'is_default', 'currency']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
