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
        Schema::create('inventory_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->constrained()
                ->cascadeOnDelete()
                ->comment('Related product ID');

            $table->enum('type', [
                'in',
                'out',
                'sale',
                'return',
                'adjust',
                'reserve',
                'release',
                'replace'
            ])->comment('Type of stock movement');

            $table->integer('quantity')
                ->comment('Quantity affected');

            $table->string('reference_type')
                ->nullable()
                ->comment('Reference source (order, supplier, etc)');

            $table->unsignedBigInteger('reference_id')
                ->nullable()
                ->comment('Reference ID from source');

            $table->text('note')
                ->nullable()
                ->comment('Additional notes or remarks');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_logs');
    }
};
