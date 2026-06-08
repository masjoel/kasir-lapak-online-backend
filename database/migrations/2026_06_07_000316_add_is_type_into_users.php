<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('is_type')->after('is_bayar')->default(0);
        });
        DB::table('users')
            ->where('email', 'owner@tokopojok.com')
            ->update(['is_type' => 3]);
        DB::table('users')
            ->whereNotNull('booking_id')
            ->where('booking_id', '<>', '')
            ->update(['is_type' => 3]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('is_type');
        });
    }
};
