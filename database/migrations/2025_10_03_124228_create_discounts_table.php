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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('discountable');
            $table->index(['discountable_id', 'discountable_type']);
            $table->enum('discount_type', ['fixed', 'percentage'])->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('discount_amount', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
