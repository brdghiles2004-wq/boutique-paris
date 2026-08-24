<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_wilaya')->nullable()->after('shipping_city');
            $table->enum('delivery_type', ['stop_desk', 'a_domicile'])->default('a_domicile')->after('shipping_wilaya');
            $table->string('guest_email')->nullable()->after('notes');
            $table->boolean('is_guest')->default(false)->after('guest_email');
        });

        // نخليو user_id اختياري (باش زوار يقدرو يديرو commande)
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_wilaya', 'delivery_type', 'guest_email', 'is_guest']);
        });
    }
};