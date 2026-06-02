<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('finished_goods', function (Blueprint $table) {
            $table->id();
            $table->foreignId('menu_id')->constrained();
            // Menautkan barang jadi ini ke ID Rencana Produksi (sebagai jejak audit)
            $table->foreignId('production_id')->nullable()->constrained(); 
            
            $table->integer('initial_quantity'); // Jumlah saat baru selesai dimasak
            $table->integer('current_quantity'); // Jumlah sisa (berkurang kalau diambil Armada)
            
            $table->date('production_date');
            $table->date('expired_date');
            
            // Sesuai request developer: menggunakan Status, bukan di-delete
            $table->enum('status', ['available', 'empty', 'expired'])->default('available');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('finished_goods');
    }
};