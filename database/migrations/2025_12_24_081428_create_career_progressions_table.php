<?php

use App\Enums\Grade;
use App\Enums\GradeLevel;
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
        Schema::create('career_progressions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                  ->constrained('employees')
                  ->cascadeOnDelete();

            $table->enum('job_grade', Grade::values())->comment('الدرجة');
            $table->enum('job_level', GradeLevel::values())->comment('المستوى');
            $table->date('grade_effective_date');        // تاريخ الدرجة
            $table->string('grade_decision_number');     // قرار الدرجة

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('career_progressions');
    }
};
