<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FinishedGood;

class ExpireFinishedGoods extends Command
{
    // Nama perintah yang akan dipanggil oleh sistem
    protected $signature = 'goods:check-expired';

    // Deskripsi perintah
    protected $description = 'Mengecek dan mengubah status barang jadi yang sudah melewati masa kedaluwarsa';

    public function handle()
    {
        // Cari barang yang statusnya 'available' tapi tanggal expired-nya SUDAH LEWAT (< hari ini)
        $expiredCount = FinishedGood::where('status', 'available')
            ->whereDate('expired_date', '<', now()->toDateString())
            ->update(['status' => 'expired']);

        $this->info("Berhasil membuang {$expiredCount} batch barang ke tong sampah digital (Expired).");
    }
}