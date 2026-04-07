<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Address type
            $table->enum('type', ['home', 'work', 'other'])->default('home');
            $table->boolean('is_default')->default(false);

            // Recipient
            $table->string('full_name');
            $table->string('phone', 20);
            $table->string('alternate_phone', 20)->nullable();

            // Address lines
            $table->string('address_line1');           // House/Flat/Building
            $table->string('address_line2')->nullable(); // Street/Area/Locality
            $table->string('landmark')->nullable();

            // Location
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('India');
            $table->string('pincode', 10);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_addresses');
    }
};
