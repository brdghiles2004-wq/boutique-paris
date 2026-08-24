<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->string('proof_image')->nullable()->after('raw_response');
            $table->string('proof_notes')->nullable()->after('proof_image');

            // نزيدو RedotPay و Mastercard للـ enum
            \DB::statement("ALTER TABLE payments MODIFY COLUMN gateway ENUM('paypal','wise','crypto','baridimob','card','cod','redotpay','mastercard')");
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['proof_image', 'proof_notes']);
        });
    }
};