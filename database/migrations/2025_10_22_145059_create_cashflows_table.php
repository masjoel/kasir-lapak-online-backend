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
        Schema::create('cashflows', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('id_order')->nullable();
            $table->string('shift')->default('pagi');
            $table->decimal('jumlah', 15, 2)->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->text('description')->nullable();
            $table->string('type')->default('income');
            $table->boolean('is_sync')->default(0);
            $table->timestamps();

            // 🔗 Relasi
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('category_id')
                ->references('id')
                ->on('cashflow_categories')
                ->onDelete('set null');

            $table->foreign('id_order')
                ->references('id')
                ->on('orders')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cashflows', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['id_order']);
        });

        Schema::dropIfExists('cashflows');
    }
};
