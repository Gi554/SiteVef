<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->string('provider')->default('stripe');
            $table->unsignedBigInteger('amount_xof')->nullable();
            $table->string('kkiapay_transaction_id')->nullable()->unique();
        });
    }

    public function down(): void
    {
        Schema::table('donations', function (Blueprint $table) {
            $table->dropUnique(['kkiapay_transaction_id']);
            $table->dropColumn(['provider', 'amount_xof', 'kkiapay_transaction_id']);
        });
    }
};
