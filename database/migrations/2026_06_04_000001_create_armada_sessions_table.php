<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('armada_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armada_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('allocated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->date('session_date');
            $table->enum('status', ['active', 'finished'])->default('active');
            $table->timestamp('started_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('armada_session_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armada_session_id')->constrained()->cascadeOnDelete();
            $table->foreignId('finished_good_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity_sent');
            $table->integer('quantity_sold')->default(0);
            $table->integer('quantity_returned')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('armada_session_items');
        Schema::dropIfExists('armada_sessions');
    }
};
