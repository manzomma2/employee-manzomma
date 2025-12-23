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
            // Drop job_group_id if it exists
            if (Schema::hasColumn('employees', 'job_group_id')) {
                $table->dropForeign(['job_group_id']);
                $table->dropColumn('job_group_id');
            }
            
            // Make sure category_group_id is properly set up
            if (!Schema::hasColumn('employees', 'category_group_id')) {
                $table->foreignId('category_group_id')
                      ->nullable()
                      ->constrained('category_groups')
                      ->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employees', function (Blueprint $table) {
            // Revert the changes if needed
            if (!Schema::hasColumn('employees', 'job_group_id')) {
                $table->foreignId('job_group_id')
                      ->nullable()
                      ->constrained('job_groups')
                      ->onDelete('set null');
            }
            
            if (Schema::hasColumn('employees', 'category_group_id')) {
                $table->dropForeign(['category_group_id']);
                $table->dropColumn('category_group_id');
            }
        });
    }
};
