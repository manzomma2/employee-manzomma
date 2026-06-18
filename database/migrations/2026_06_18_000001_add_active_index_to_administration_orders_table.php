<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('administration_orders', function (Blueprint $table) {
            $table->index('active', 'administration_orders_active_index');
        });
    }

    public function down(): void
    {
        Schema::table('administration_orders', function (Blueprint $table) {
            $table->dropIndex('administration_orders_active_index');
        });
    }
};
