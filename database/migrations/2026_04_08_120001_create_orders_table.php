<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('session_id')->nullable()->index();

            $table->foreignId('coupon_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->string('coupon_code', 50)->nullable();
            $table->enum('coupon_type', ['percentage', 'fixed'])->nullable();
            $table->decimal('coupon_value', 10, 2)->default(0);

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('discount', 10, 2)->default(0);
            $table->decimal('shipping_amount', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->enum('payment_method', ['cod', 'online'])->default('cod');
            $table->enum('payment_status', ['pending', 'paid', 'failed'])->default('pending');
            $table->enum('status', ['pending', 'confirmed', 'packed', 'shipped', 'returned', 'on-the-way', 'delivered', 'cancelled'])->default('pending');
            $table->enum('delivery_type', ['in_hand_delivery', 'third_party'])->default('in_hand_delivery');
            $table->string('delivery_partner')->nullable();
            $table->string('delivery_boy_name')->nullable();
            $table->string('delivery_boy_phone', 20)->nullable();
            $table->string('awb_number')->nullable();
            $table->string('tracking_url')->nullable();

            $table->string('customer_name');
            $table->string('customer_phone', 20);
            $table->string('customer_email')->nullable();
            $table->string('address_line1');
            $table->string('address_line2')->nullable();
            $table->string('landmark')->nullable();
            $table->string('city');
            $table->string('state');
            $table->string('country')->default('India');
            $table->string('pincode', 10);

            $table->text('customer_note')->nullable();

            $table->timestamp('placed_at')->nullable();
            $table->timestamp('estimated_delivery_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
