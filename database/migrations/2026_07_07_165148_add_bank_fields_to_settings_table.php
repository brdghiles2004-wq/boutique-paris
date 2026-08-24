<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN gateway ENUM('paypal','wise','crypto','baridimob','card','cod','redotpay','mastercard','satim','cpa','bdl','badr','bna','agb','sg')");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE payments MODIFY COLUMN gateway ENUM('paypal','wise','crypto','baridimob','card','cod','redotpay','mastercard')");
    }
};