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
        Schema::create('schools', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('ministry_code', 25)->unique();
            $table->enum('gender', ['male', 'female'])->default('male');
            $table->string('stage')->comment('المراحل الدراسية');
            $table->boolean('is_complex')->default(false);
            $table->string('school_type')->comment('نوع المدرسة');
            $table->string('building_type')->comment('نوع المبنى');
            $table->string('status')->default('active');
            $table->string('educational_sector')->comment('القطاع التعليمي داخل المنطقة');
            $table->foreignId('coordinator_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('principal_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
