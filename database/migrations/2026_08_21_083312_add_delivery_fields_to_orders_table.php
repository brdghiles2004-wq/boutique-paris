<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('tracking_number')->nullable()->after('status');
            $table->string('delivery_company')->nullable()->after('tracking_number');
            $table->string('delivery_status')->nullable()->after('delivery_company');
            $table->decimal('delivery_price', 10, 2)->nullable()->after('delivery_status');
            $table->timestamp('shipped_at')->nullable()->after('delivery_price');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'tracking_number', 'delivery_company',
                'delivery_status', 'delivery_price', 'shipped_at',
            ]);
        });
    }
};