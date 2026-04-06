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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            // BASIC INFO
            $table->foreignId('category_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->comment('Related category ID');
                
            $table->string('name')->comment('Product name');
            $table->string('slug')->unique()->comment('SEO-friendly unique slug');
            $table->text('description')->nullable()->comment('Product description');

            // PRICING 
            $table->decimal('cost_price', 10, 2)
                ->default(0)
                ->comment('Purchase cost price');

            $table->decimal('selling_price', 10, 2)
                ->default(0)
                ->comment('Final price at which product is sold');

            $table->decimal('compare_price', 10, 2)
                ->nullable()
                ->comment('Original price for discount display (cut price)');

            // STOCK 
            $table->integer('stock')
                ->default(0)
                ->comment('Current available stock');

            $table->integer('stock_in')
                ->default(0)
                ->comment('Total stock added over time');

            $table->integer('stock_out')
                ->default(0)
                ->comment('Total stock removed/sold');

            // FOMO
            $table->integer('hurry_stock')
                ->nullable()
                ->comment('Threshold to trigger low stock message (Only X left)');

            // GLOBAL STOCK CONTROL 
            $table->boolean('is_out_of_stock')
                ->default(false)
                ->comment('Manual override to mark product as out of stock');

            // STATUS 
            $table->enum('status', ['active', 'inactive', 'draft'])
                ->default('active')
                ->comment('Product visibility status');

            // OPTIONAL FLAGS
            $table->boolean('is_featured')
                ->default(false)
                ->comment('Mark product as featured');

            $table->boolean('is_trending')
                ->default(false)
                ->comment('Mark product as trending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
