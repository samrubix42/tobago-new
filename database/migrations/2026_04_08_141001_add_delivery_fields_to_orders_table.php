<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('delivery_partner')->nullable()->after('status');
            $table->string('delivery_boy_name')->nullable()->after('delivery_partner');
            $table->string('delivery_boy_phone', 20)->nullable()->after('delivery_boy_name');
            $table->string('awb_number')->nullable()->after('delivery_boy_phone');
            $table->string('tracking_url')->nullable()->after('awb_number');
            $table->timestamp('estimated_delivery_at')->nullable()->after('placed_at');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'delivery_partner',
                'delivery_boy_name',
                'delivery_boy_phone',
                'awb_number',
                'tracking_url',
                'estimated_delivery_at',
            ]);
        });
    }
};
