<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jointheteam@aglet.co.za'],
            [
                'name' => 'Join The Team',
                'password' => Hash::make('@TeamAglet'),
                'email_verified_at' => now(),
            ]
        );
    }
} 