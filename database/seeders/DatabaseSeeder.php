<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar a los seeders en orden
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
        ]);

        // Crear usuario de prueba adicional si es necesario
        $estudianteRole = Role::where('name', 'estudiante')->first();
        if ($estudianteRole) {
            User::create([
                'name' => 'Test User',
                'email' => 'test@example.com',
                'password' => Hash::make('password'),
                'role_id' => $estudianteRole->id,
                'email_verified_at' => now(),
            ]);
        }
    }
}
