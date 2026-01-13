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
        Schema::create('program_cycle_indicators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_cycle_id')->constrained('program_cycles')->cascadeOnDelete();

            $table->string('title');
            $table->string('target_value')->nullable();
            $table->string('actual_value')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();

            $table->index('program_cycle_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_cycle_indicators');
    }
};
