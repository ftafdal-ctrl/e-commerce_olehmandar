<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->enum('weight_option', ['500g', '1kg'])->default('500g')->after('quantity');
            $table->decimal('price_at_add', 12, 2)->nullable()->after('weight_option')->comment('Harga saat item ditambah ke keranjang');
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->enum('weight_option', ['500g', '1kg'])->default('500g')->after('quantity');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropColumn(['weight_option', 'price_at_add']);
        });

        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn('weight_option');
        });
    }
};
