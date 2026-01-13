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
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->restrictOnDelete();
            $table->foreignId('user_id')->constrained('users')->restrictOnDelete();
            $table->foreignId('academic_year_id')->constrained('academic_years')->restrictOnDelete();

            // إن رغبت: ربط اختياري بنسخة برنامج في نفس العام (ليس إلزاميًا)
            $table->foreignId('program_cycle_id')->nullable()->constrained('program_cycles')->nullOnDelete();

            $table->date('visit_date');
            $table->string('visit_type');
            $table->string('objective')->nullable();
            $table->text('notes')->nullable();
            $table->text('recommendations')->nullable();

            $table->timestamps();

            $table->index(['user_id', 'academic_year_id', 'visit_date']);
            $table->index(['school_id', 'academic_year_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('visits');
    }
};
