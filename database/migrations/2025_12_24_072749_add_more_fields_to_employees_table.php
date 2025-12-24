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
        Schema::table('employees', function (Blueprint $table) {
            $table->enum('marital_status', ['عزباء','أعزب', 'متزوجة','متزوج','مطلقة', 'مطلق','أرملة', 'أرمل'])->nullable()->after('phone');
            $table->enum('religion', ['مسلم', 'مسيحي'])->nullable()->after('marital_status');
            $table->text('address')->nullable()->after('religion');
            $table->string('academic_qualification', 100)->nullable()->after('address');
            $table->string('academic_specialization', 255)->nullable()->after('academic_qualification');
            $table->date('graduation_date')->nullable()->after('academic_specialization');
            $table->date('birth_date')->nullable()->after('graduation_date');
            $table->string('appointment_decision_number', 100)->nullable()->after('birth_date');
            $table->enum('type', ['ذكر', 'انثى'])->nullable()->after('appointment_decision_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropColumn([
                'marital_status',
                'religion',
                'address',
                'academic_qualification',
                'academic_specialization',
                'graduation_date',
                'birth_date',
                'appointment_decision_number',
                'type'
            ]);
        });
    }
};
