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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable()->index();
            $table->string('email')->nullable()->index();
            $table->string('phone')->nullable();
            $table->string('company')->nullable();
            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->text('address')->nullable();
            $table->decimal('balance', 10, 2)->default(0);
            $table->decimal('credit_balance', 10, 2)->default(0);
            $table->string('payment_method')->nullable();
            $table->string('status')->default('active');
            $table->string('contact_method')->default('email')->comment('Preferred contact method');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
