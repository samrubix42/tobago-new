<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->enum('type', [
                'in',
                'out',
                'sale',
                'return',
                'adjust'
            ])->comment('Type of stock movement')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inventory_logs', function (Blueprint $table) {
            $table->enum('type', [
                'in',
                'out',
                'sale',
                'return',
                'adjust',
                'reserve',
                'release',
                'replace'
            ])->comment('Type of stock movement')->change();
        });
    }
};
