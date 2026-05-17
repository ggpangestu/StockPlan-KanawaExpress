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
        Schema::create('raw_materials', function (Blueprint $table) {

            $table->id();

            // FOTO
            $table->string('image')->nullable();

            // IDENTITAS
            $table->string('name');

            // KATEGORI
            $table->string('category');

            // STOK
            $table->integer('sealed_stock')->default(0);

            $table->decimal('opened_stock', 12, 2)
                ->default(0);

            // SATUAN
            $table->string('purchase_unit');

            $table->string('base_unit');

            // KONVERSI
            $table->decimal('conversion_value', 12, 2);

            // MINIMUM STOCK
            $table->decimal('minimum_stock', 12, 2)
                ->default(0);

            // HARGA TERAKHIR
            $table->decimal('latest_price', 12, 2)
                ->default(0);
            // STATUS
            $table->boolean('is_active')
                ->default(true);

            // CREATED BY
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('raw_materials');
    }
};
