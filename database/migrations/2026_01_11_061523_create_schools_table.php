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
            $table->string('ministry_code', 25)->comment('الرقم الوزاري')->unique();
            $table->enum('gender', ['بنين', 'بنات'])->default('بنين');
            $table->string('stage')->comment('المراحل الدراسية');
            $table->boolean('is_complex')->comment('المدرسة ضمن مجمع تعليمي')->default(false);
            $table->string('school_type')->comment('نوع المدرسة');
            $table->string('building_type')->comment('نوع المبنى');
            $table->enum('status', ['نشط', 'غير نشط'])->default('نشط');
            $table->foreignId('sector_id')->nullable()->constrained('sectors')->nullOnDelete()->comment('القطاع التعليمي'); // إذا حُذف القطاع تصبح null بدل منع الحذف
            $table->foreignId('coordinator_id')->comment('منسق الموهوبين')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('principal_id')->comment('مدير المدرسة')->nullable()->constrained('users')->nullOnDelete();
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
