<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table(
            'raw_material_transactions',
            function (Blueprint $table) {

                $table->decimal(
                    'purchase_quantity',
                    12,
                    2
                )
                ->nullable()
                ->after('quantity');

            }
        );
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table(
            'raw_material_transactions',
            function (Blueprint $table) {

                $table->dropColumn(
                    'purchase_quantity'
                );

            }
        );
    }
};