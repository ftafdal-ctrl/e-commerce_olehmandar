<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->decimal('price_500g', 12, 2)->nullable()->after('price')->comment('Harga untuk 500 gram');
            $table->decimal('price_1kg', 12, 2)->nullable()->after('price_500g')->comment('Harga untuk 1 kilogram');
        });

        DB::table('products')->update([
            'price_500g' => DB::raw('price'),
            'price_1kg' => DB::raw('price * 2'),
        ]);
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['price_500g', 'price_1kg']);
        });
    }
};
