<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('armada_sessions', 'allocated_by')) {
            Schema::table('armada_sessions', function (Blueprint $table) {
                $table->foreignId('allocated_by')
                    ->nullable()
                    ->after('armada_user_id')
                    ->constrained('users')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('armada_sessions', 'allocated_by')) {
            Schema::table('armada_sessions', function (Blueprint $table) {
                $table->dropConstrainedForeignId('allocated_by');
            });
        }
    }
};
