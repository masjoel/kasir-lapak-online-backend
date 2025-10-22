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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('invoice')->nullable();
            $table->string('customer')->nullable();
            $table->integer('nominal')->nullable();
            $table->integer('total_hpp')->nullable();
            $table->integer('kembali')->default(0);
            $table->string('payment_method')->nullable();
            $table->integer('total_item')->nullable();
            $table->unsignedBigInteger('id_kasir')->nullable();
            $table->string('nama_kasir')->nullable();
            $table->timestamp('transaction_time')->nullable();
            $table->boolean('is_sync')->default(0);
            $table->boolean('is_bayar')->default(1);
            $table->text('keterangan')->nullable();
            $table->timestamps();

            // 🔗 Relasi ke tabel users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            // 🔗 Relasi ke kasir (juga dari tabel users jika satu tabel)
            $table->foreign('id_kasir')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['id_kasir']);
        });

        Schema::dropIfExists('orders');
    }
};
