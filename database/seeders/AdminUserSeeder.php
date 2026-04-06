<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@techonika.com'],
            [
                'name' => 'Tobac-Go Admin',
                'password' => Hash::make('123456789'),
                'is_admin' => true,
            ]
        );
    }
}
