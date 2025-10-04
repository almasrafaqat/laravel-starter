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
        Schema::create('checklistables', function (Blueprint $table) {
            $table->id();

            $table->foreignId('checklist_id')
                ->constrained('checklists')
                ->onDelete('cascade');

            $table->morphs('checklistable');

            $table->boolean('is_completed')
                ->default(false);
            $table->timestamp('completed_at')
                ->nullable();
            $table->timestamps();

            $table->unique([
                'checklist_id',
                'checklistable_id',
                'checklistable_type'
            ], 'checklistable_unique');

            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklistables');
    }
};
