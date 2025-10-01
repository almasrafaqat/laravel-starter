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
        Schema::create('plan_metas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('plan_id')->index();
            $table->string('meta_key')->index();
            $table->text('meta_value')->nullable();
            $table->json('meta_data')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->boolean('is_default')->default(false)->index();
            $table->integer('meta_order')->default(0)->index();
            $table->string('meta_group')->nullable()->index();
            $table->string('meta_type')->nullable()->index();
            $table->unsignedBigInteger('created_by')->nullable()->index();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();
            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('cascade');
            $table->timestamps();

            // Composite index for fast lookup by plan and meta_key
            $table->index(['plan_id', 'meta_key']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plan_metas');
    }
};
