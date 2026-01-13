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
        Schema::create('work_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();
            $table->foreignId('program_cycle_id')->nullable()->constrained('program_cycles')->nullOnDelete();

            $table->date('event_date'); // تاريخ الحدث
            $table->string('event_type'); // لقاء علمي، ورشة، انتداب...
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('location')->nullable();

            $table->time('starts_at')->nullable();
            $table->time('ends_at')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'academic_year_id', 'event_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_events');
    }
};
