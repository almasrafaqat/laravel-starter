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
        Schema::create('invoices', function (Blueprint $table) {

            $table->id();
            $table->string('title')->nullable();

            $table->string('invoice_number')->nullable();

            $table->unsignedBigInteger('template_id')->nullable();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('set null');

            $table->date('date')->nullable();
            $table->date('valid_until')->nullable();

            $table->foreignId('customer_id')
                ->nullable()
                ->constrained()
                ->onDelete('cascade'); // FK to customers

            $table->foreignId('company_id')
                ->nullable()
                ->constrained()
                ->onDelete('set null'); // FK to companies


            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->onDelete('set null'); // FK to users (creator)


            $table->string('payment_status')->default('unpaid');
            $table->string('status')->default('draft');
            $table->date('paid_on')->nullable();

            $table->string('payment_method')->default('cash');
            $table->string('reference')->nullable();
            $table->text('description')->nullable();

            $table->string('timeframe')->nullable();
            $table->string('importance')->nullable();




            $table->decimal('amount_paid', 10, 2)->default(0);
            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('tax_rate', 5, 2)->default(0);
            $table->decimal('tax_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);
            $table->decimal('balance_due', 10, 2)->default(0);

            $table->string('currency', 3)->default('USD');
            $table->decimal('currency_rate', 10, 6)->default(1);
            $table->decimal('total_pkr', 10, 2)->default(1);
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};
