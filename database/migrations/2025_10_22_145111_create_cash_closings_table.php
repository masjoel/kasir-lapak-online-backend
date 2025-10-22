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
       Schema::create('cash_closings', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('shift')->nullable();
            $table->decimal('total_pemasukan', 15, 2)->nullable();
            $table->decimal('total_pengeluaran', 15, 2)->nullable();
            $table->decimal('saldo_sistem', 15, 2)->nullable();
            $table->decimal('saldo_fisik', 15, 2)->nullable();
            $table->decimal('selisih', 15, 2)->nullable();
            $table->text('catatan')->nullable();
            $table->boolean('is_sync')->default(0);
            $table->timestamps();

            // 🔗 Relasi ke users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cash_closings', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('cash_closings');
    }
};
