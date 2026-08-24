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
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();

            $table->enum('status', [
                'pending', 'processing', 'paid', 'shipped', 'delivered', 'cancelled', 'refunded',
            ])->default('pending');

            $table->decimal('subtotal', 10, 2);
            $table->decimal('shipping_cost', 8, 2)->default(0);
            $table->decimal('total', 10, 2);
            $table->string('currency', 3)->default('DZD');

            $table->string('shipping_name');
            $table->string('shipping_phone');
            $table->text('shipping_address');
            $table->string('shipping_city');
            $table->string('shipping_postal_code')->nullable();
            $table->string('shipping_country')->default('Algérie');

            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};