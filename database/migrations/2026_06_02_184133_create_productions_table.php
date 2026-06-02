<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('productions', function (Blueprint $table) {
            $table->id();
            $table->date('plan_date'); // Tanggal target diproduksi
            $table->enum('status', ['planned', 'processing', 'completed', 'cancelled'])->default('planned');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
        });

        Schema::create('production_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('production_id')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_id')->constrained();
            $table->integer('target_quantity'); // Target porsi/botol dari owner
            $table->integer('actual_quantity')->default(0); // Hasil aktual dari dapur nantinya
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('production_items');
        Schema::dropIfExists('productions');
    }
};
