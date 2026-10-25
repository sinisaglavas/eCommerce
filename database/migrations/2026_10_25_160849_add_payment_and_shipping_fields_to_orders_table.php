<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_status')->default('unpaid')->after('status');
            $table->string('payment_method')->nullable()->after('payment_status');
            $table->string('tracking_number')->nullable()->unique()->after('payment_method');
            $table->string('shipping_carrier')->nullable()->after('tracking_number');
            $table->timestamp('estimated_delivery_at')->nullable()->after('shipping_carrier');
            $table->timestamp('shipped_at')->nullable()->after('estimated_delivery_at');
            $table->timestamp('delivered_at')->nullable()->after('shipped_at');
            $table->index(['payment_status', 'created_at']);
        });

        DB::table('orders')->where('status', 'paid')->update(['payment_status' => 'paid']);
        DB::table('orders')->where('status', 'payment_failed')->update(['payment_status' => 'failed']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex('orders_payment_status_created_at_index');
            $table->dropUnique('orders_tracking_number_unique');
            $table->dropColumn([
                'payment_status',
                'payment_method',
                'tracking_number',
                'shipping_carrier',
                'estimated_delivery_at',
                'shipped_at',
                'delivered_at',
            ]);
        });
    }
};
