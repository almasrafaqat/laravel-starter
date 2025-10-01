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
        Schema::create('company_metas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id');


            $table->string('meta_key');
            $table->text('meta_value')->nullable();
            $table->json('meta_data')->nullable();

            $table->boolean('is_active')->default(true);
            $table->boolean('is_default')->default(false);
            $table->integer('meta_order')->default(0);
            $table->string('meta_group')->nullable();
            $table->string('meta_type')->nullable();

            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('deleted_by')->nullable();

            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('company_metas');
    }
};
