<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::table('menus', function (Blueprint $table) {
        $table->integer('expires_in_days')->default(1)->after('price'); 
        // default 1 hari (besoknya basi)
    });
}

public function down(): void
{
    Schema::table('menus', function (Blueprint $table) {
        $table->dropColumn('expires_in_days');
    });
}
};
