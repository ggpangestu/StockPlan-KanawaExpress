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
    Schema::table('production_items', function (Blueprint $table) {
        // Menambahkan kolom wasted_quantity setelah target_quantity
        $table->integer('wasted_quantity')->default(0)->after('target_quantity');
    });
}

public function down(): void
{
    Schema::table('production_items', function (Blueprint $table) {
        $table->dropColumn('wasted_quantity');
    });
}
};
