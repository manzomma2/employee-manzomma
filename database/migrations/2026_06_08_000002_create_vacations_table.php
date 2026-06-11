<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vacations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->onDelete('cascade');
            $table->foreignId('vacation_type_id')->constrained('vacation_types')->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('pre_end_date')->nullable();
            $table->text('notes')->nullable();
            $table->text('extension_notes')->nullable();
            $table->text('cut_note')->nullable();
            $table->tinyInteger('returning')->default(0)
                ->comment('0 = not returned, 1 = returned');
            $table->enum('status', ['active', 'scedual', 'completed'])->default('active');
            $table->timestamps();

            $table->index('status');
            $table->index('vacation_type_id');
            $table->index('start_date');
            $table->index('end_date');
            $table->index(['employee_id', 'status']);
            $table->index(['start_date', 'end_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vacations');
    }
};
