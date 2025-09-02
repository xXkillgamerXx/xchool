<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener los roles
        $colegioRole = Role::where('name', 'colegio')->first();
        $profesorRole = Role::where('name', 'profesor')->first();
        $padreRole = Role::where('name', 'padre')->first();
        $estudianteRole = Role::where('name', 'estudiante')->first();
        $suplidorRole = Role::where('name', 'suplidor')->first();

        // Usuario con email específico (Ronny Correa)
        User::create([
            'name' => 'Ronny Correa',
            'email' => 'ronnycorreaunity@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $colegioRole->id,
            'email_verified_at' => now(),
        ]);

        // Usuario Profesor
        User::create([
            'name' => 'María González',
            'email' => 'maria.gonzalez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $profesorRole->id,
            'email_verified_at' => now(),
        ]);

        // Usuario Padre
        User::create([
            'name' => 'Carlos Rodríguez',
            'email' => 'carlos.rodriguez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $padreRole->id,
            'email_verified_at' => now(),
        ]);

        // Usuario Estudiante
        User::create([
            'name' => 'Ana Martínez',
            'email' => 'ana.martinez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $estudianteRole->id,
            'email_verified_at' => now(),
        ]);

        // Usuario Suplidor
        User::create([
            'name' => 'Luis Pérez',
            'email' => 'luis.perez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $suplidorRole->id,
            'email_verified_at' => now(),
        ]);
    }
}
