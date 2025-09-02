<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear usuario con email específico
        User::create([
            'name' => 'Ronny Correa',
            'email' => 'ronnycorreaunity@gmail.com',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
        ]);
    }
}
