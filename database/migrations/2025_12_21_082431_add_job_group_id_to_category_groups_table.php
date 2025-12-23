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
        Schema::table('category_groups', function (Blueprint $table) {
            $table->foreignId('job_group_id')->after('id')->nullable()->constrained()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('category_groups', function (Blueprint $table) {
            $table->dropForeign(['job_group_id']);
            $table->dropColumn('job_group_id');
        });
    }
};
