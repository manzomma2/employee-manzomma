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
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('grade', Grade::values())->nullable()->after('joining_date');
            $table->enum('grade_level', GradeLevel::values())->nullable()->after('grade');
            $table->date('grade_date')->nullable()->after('grade_level');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn(['grade', 'grade_level', 'grade_date']);
        });
    }
};
