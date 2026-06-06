<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // 1. Buat Tabel Baru untuk mencatat bahan mentah yang tumpah
        Schema::create('production_wastes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2); // Decimal agar bisa input 10.5 ml dll
            $table->timestamps();
        });

        // 2. Hapus kolom wasted_quantity yang lama di tabel items agar rapi
        if (Schema::hasColumn('production_items', 'wasted_quantity')) {
            Schema::table('production_items', function (Blueprint $table) {
                $table->dropColumn('wasted_quantity');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('production_wastes');
    }
};