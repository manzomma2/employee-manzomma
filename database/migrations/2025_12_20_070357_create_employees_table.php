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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('file_number')->unique()->nullable();
            $table->string('job_title')->nullable();
            $table->bigInteger('national_id')->nullable();
            $table->string('insurance_number')->nullable();//الرقم التأميني
            $table->text('photo')->nullable();
            $table->string('phone')->nullable();
            $table->date('hire_date')->nullable();
            $table->date('contract_date')->nullable();
            $table->date('joining_date')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
