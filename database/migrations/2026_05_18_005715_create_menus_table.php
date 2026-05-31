<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Menu
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('category'); // Coffee, Non-Coffee, Pastry, dll.
            $table->decimal('price', 12, 2);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        // Tabel Pivot (Resep / Ingredients)
        Schema::create('menu_raw_material', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained()->cascadeOnDelete();
            $table->foreignId('raw_material_id')->constrained()->cascadeOnDelete();
            $table->decimal('quantity', 10, 2); // Jumlah bahan baku yang dipakai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_raw_material');
        Schema::dropIfExists('menus');
    }
};
