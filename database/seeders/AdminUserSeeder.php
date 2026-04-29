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
            ['email' => 'techonika.com@gmail.com'],
            [
                'name' => 'Tobac-Go Admin',
                'password' => Hash::make('tobacgo!@#$%!'),
                'is_admin' => true,
            ]
        );
    }
}
