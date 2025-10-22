<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        User::truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        User::create(
            [
                'name' => 'Administrator',
                'username' => 'admin',
                'email' => 'admin@tokopojok.com',
                'password' => Hash::make('admin12345'),
                'roles' => 'admin',
            ]
        );
        User::create(
            [
                'name' => 'Staf',
                'username' => 'staf',
                'email' => 'staf@staf.com',
                'password' => Hash::make('staf12345'),
                'roles' => 'staf',
            ]
        );
    }
}
