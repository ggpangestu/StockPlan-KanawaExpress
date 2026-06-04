<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('finished_goods', function (Blueprint $table) {
            $table->string('status')->default('available')->change();
        });

        Schema::create('return_checks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('armada_session_item_id')->constrained()->cascadeOnDelete();
            $table->foreignId('finished_good_id')->constrained()->cascadeOnDelete();
            $table->foreignId('armada_user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('checked_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('rejected_finished_good_id')->nullable()->constrained('finished_goods')->nullOnDelete();
            $table->integer('quantity');
            $table->enum('status', ['pending', 'ready', 'expired_damaged'])->default('pending');
            $table->timestamp('checked_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('return_checks');

        Schema::table('finished_goods', function (Blueprint $table) {
            $table->enum('status', ['available', 'empty', 'expired'])->default('available')->change();
        });
    }
};
