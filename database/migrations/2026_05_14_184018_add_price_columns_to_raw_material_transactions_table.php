<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('raw_material_transactions', function (Blueprint $table) {

            $table->decimal('unit_price', 12, 2)
                ->nullable()
                ->after('quantity');

            $table->decimal('total_price', 12, 2)
                ->nullable()
                ->after('unit_price');

        });
    }

    public function down(): void
    {
        Schema::table('raw_material_transactions', function (Blueprint $table) {

            $table->dropColumn([
                'unit_price',
                'total_price',
            ]);

        });
    }
};