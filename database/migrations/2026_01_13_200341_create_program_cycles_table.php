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
        Schema::create('program_cycles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->constrained()->cascadeOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();

            $table->tinyInteger('term')->nullable(); // 1 أو 2 (أو تتركه null)
            $table->enum('status', ['in_progress', 'completed', 'suspended', 'canceled'])->default('in_progress');

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->unique(['program_id', 'academic_year_id', 'term'], 'unique_program_cycle');
            $table->index(['academic_year_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_cycles');
    }
};
