<?php

namespace Database\Seeders;

use App\Models\ActivationCode;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivationCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate 10 activation codes for each type
        $types = ['starter', 'basic', 'pro'];
        foreach ($types as $type) {
            for ($i = 0; $i < 10; $i++) {
                // jika starter = 8 digit, basic = 10 digit, pro = 12 digit
                // 4 bytes = 8 hex chars, 5 bytes = 10 hex chars, 6 bytes = 12 hex chars
                ActivationCode::create([
                    'code' => strtoupper(bin2hex(random_bytes(
                        $type === 'starter' ? 4 : ($type === 'basic' ? 5 : 6)
                    ))),
                    'type' => $type,
                    'is_used' => false,
                ]);
            }
        }
    }
}
