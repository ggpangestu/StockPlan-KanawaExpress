<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Karyawan ini akan bekerja setiap hari pada jam 00:01 dini hari
Schedule::command('goods:check-expired')->dailyAt('00:01');
