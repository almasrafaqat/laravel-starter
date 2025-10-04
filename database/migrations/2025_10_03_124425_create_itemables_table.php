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
        Schema::create('itemables', function (Blueprint $table) {
            $table->id();
            $table->morphs('itemable');
            $table->foreignId('item_id')
                ->constrained()
                ->onDelete('cascade');
            $table->timestamps();

            $table->index(['itemable_id', 'itemable_type']);
            $table->index('item_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('itemables');
    }
};
