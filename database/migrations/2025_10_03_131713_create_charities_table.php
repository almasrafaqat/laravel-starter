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
        Schema::create('charities', function (Blueprint $table) {
            $table->id();

            // Foreign key relationship
            $table->foreignId('invoice_id')
                ->nullable()
                ->constrained('invoices')
                ->onDelete('cascade');

            $table->nullableMorphs('charitable');

            // Charity details
            $table->string('cause_name')->nullable();
            $table->string('type')->default('percentage'); // 'percentage' or 'fixed'
            $table->decimal('value', 10, 6)->default(1.000000);
            // Amount fields
            $table->decimal('amount_usd', 10, 2)->default(0.00);
            $table->decimal('amount_pkr', 10, 2)->default(0.00);
            $table->decimal('paid', 10, 2)->default(0.00);
            $table->decimal('remaining', 10, 2)->default(0.00);
            $table->decimal('currency_rate', 10, 6)->default(1.000000);

            // Contribution tracking
            $table->boolean('is_contributed')->default(false);
            $table->date('contribution_date')->nullable();
            $table->text('notes')->nullable();

            // Timestamps
            $table->timestamps();

            // Optional: Add index for better performance
            $table->index('invoice_id');
            $table->index('is_contributed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('charities');
    }
};
