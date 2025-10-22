<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Perusahaan;
use App\Models\User;
use Ramsey\Uuid\Uuid;
use App\Models\ProfilUsaha;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Buat user pertama
        $user = User::create([
            'name' => 'Owner',
            'username' => 'admin',
            'email' => 'owner@tokopojok.com',
            'password' => Hash::make('admin12345'),
            'roles' => 'admin',
        ]);
        Perusahaan::create([
            'user_id' => $user->id,
            'nama_client' => 'Kasir Lapak',
            'desc_app' => 'murah enak halal',
            'alamat_client' => 'Jl. Raya No.1',
            'signature' => 'Direktur',
            'email' => 'admin@toko.com',
            'phone' => '08123456789',
            'logo' => 'image/icon-foto.png',
            'image_icon' => 'image/icon-lkp2mpd.png',
            'footnot' => 'Terimakasih'
        ]);
        // $this->call([
        //     UserSeeder::class,
        // ]);
    }
}
