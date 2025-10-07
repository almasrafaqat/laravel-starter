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
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->nullableMorphs('taxable');
            $table->index(['taxable_id', 'taxable_type']);
            $table->enum('tax_type', ['fixed', 'percentage'])->nullable();
            $table->string('tax_name')->nullable();
            $table->decimal('tax_value', 10, 2)->nullable();
            $table->decimal('tax_amount', 10, 2)->nullable();
            $table->timestamps();

            // Add additional indexes if needed:
            $table->index('tax_type');
            $table->index('tax_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
};
