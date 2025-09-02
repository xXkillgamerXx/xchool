<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'colegio',
                'display_name' => 'Colegio',
                'description' => 'Administrador del colegio con acceso completo al sistema',
            ],
            [
                'name' => 'profesor',
                'display_name' => 'Profesor',
                'description' => 'Profesor que puede gestionar cursos y estudiantes',
            ],
            [
                'name' => 'padre',
                'display_name' => 'Padre',
                'description' => 'Padre que puede ver información de sus hijos',
            ],
            [
                'name' => 'estudiante',
                'display_name' => 'Estudiante',
                'description' => 'Estudiante que puede ver sus notas y tareas',
            ],
            [
                'name' => 'suplidor',
                'display_name' => 'Suplidor',
                'description' => 'Proveedor de servicios para el colegio',
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
