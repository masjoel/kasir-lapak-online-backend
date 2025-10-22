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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); 
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('name')->nullable();
            $table->integer('hpp')->nullable();
            $table->integer('price')->nullable();
            $table->integer('stock')->nullable();
            $table->string('image')->nullable();
            $table->string('category')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->boolean('is_best_seller')->nullable();
            $table->boolean('is_sync')->default(0);
            $table->string('satuan')->nullable();
            $table->unsignedBigInteger('product_unit_id')->nullable();
            $table->integer('discount')->nullable();
            $table->boolean('is_discount')->default(0);
            $table->text('keterangan')->nullable();
            $table->string('product_code')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('set null');

            $table->foreign('product_unit_id')
                ->references('id')
                ->on('product_units')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['category_id']);
            $table->dropForeign(['product_unit_id']);
        });

        Schema::dropIfExists('products');
    }
};
