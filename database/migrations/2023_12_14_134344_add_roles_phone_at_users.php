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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->enum('roles', ['admin', 'staff', 'user', 'kasir', 'reseller'])->default('user')->after('phone');
            $table->string('device_id')->nullable()->after('remember_token')->default('0');
            $table->string('marketing')->nullable()->after('device_id')->default('0');
            $table->string('booking_id')->after('marketing')->nullable();
            $table->string('reseller_id')->after('booking_id')->nullable();
            $table->string('telpon')->nullable()->after('reseller_id');
            $table->string('address')->nullable()->after('telpon');
            $table->string('bank')->nullable()->after('address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'roles', 'device_id', 'marketing', 'booking_id', 'reseller_id', 'telpon', 'address', 'bank']);
        });
    }
};
