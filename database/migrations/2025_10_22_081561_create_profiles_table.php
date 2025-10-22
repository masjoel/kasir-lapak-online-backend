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
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('name')->nullable();
            $table->string('tagline')->nullable();
            $table->text('alamat')->nullable();
            $table->text('footnote')->nullable();
            $table->string('qris')->nullable();
            $table->string('logo')->nullable();
            $table->string('image')->nullable();
            $table->boolean('is_sync')->default(0);
            $table->timestamps();

            // 🔗 Relasi foreign key ke tabel users
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::dropIfExists('profiles');
    }
};
