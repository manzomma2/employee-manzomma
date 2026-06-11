<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacation_hospitals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vacation_id')->unique()->constrained('vacations')->onDelete('cascade');
            $table->foreignId('hospital_id')->constrained('hospitals')->onDelete('cascade');
            $table->text('diagnoses');
            $table->timestamps();

            $table->index('hospital_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacation_hospitals');
    }
};
