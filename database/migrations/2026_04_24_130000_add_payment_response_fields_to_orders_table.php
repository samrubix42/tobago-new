<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_gateway', 40)->nullable()->after('payment_method');
            $table->string('payment_gateway_order_id')->nullable()->after('payment_gateway');
            $table->string('payment_gateway_transaction_id')->nullable()->after('payment_gateway_order_id');
            $table->string('payment_state', 40)->nullable()->after('payment_status');
            $table->text('payment_failure_reason')->nullable()->after('payment_state');
            $table->json('payment_response_payload')->nullable()->after('payment_failure_reason');
            $table->timestamp('payment_verified_at')->nullable()->after('payment_response_payload');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'payment_gateway',
                'payment_gateway_order_id',
                'payment_gateway_transaction_id',
                'payment_state',
                'payment_failure_reason',
                'payment_response_payload',
                'payment_verified_at',
            ]);
        });
    }
};

