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
        Schema::create('product_images', function (Blueprint $table) {
            $table->id();

    $table->foreignId('product_id')
          ->constrained()
          ->cascadeOnDelete()
          ->comment('Related product ID');

    $table->string('image')
          ->comment('Image file path');

    $table->boolean('is_primary')
          ->default(false)
          ->comment('Is this the main display image');

    $table->integer('sort_order')
          ->default(0)
          ->comment('Sorting order for images');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_images');
    }
};
