<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Role;
use App\Models\User;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles
        $colegioRole = Role::create([
            'name' => 'colegio',
            'display_name' => 'Colegio',
            'description' => 'Administrador del colegio'
        ]);

        $profesorRole = Role::create([
            'name' => 'profesor',
            'display_name' => 'Profesor',
            'description' => 'Profesor del colegio'
        ]);

        $padreRole = Role::create([
            'name' => 'padre',
            'display_name' => 'Padre',
            'description' => 'Padre de familia'
        ]);

        $estudianteRole = Role::create([
            'name' => 'estudiante',
            'display_name' => 'Estudiante',
            'description' => 'Estudiante del colegio'
        ]);

        // Crear usuario colegio
        $colegio = User::create([
            'name' => 'admin',
            'first_name' => 'Ronny',
            'last_name' => 'Correa',
            'email' => 'ronnycorreaunity@gmail.com',
            'password' => Hash::make('password'),
            'role_id' => $colegioRole->id,
        ]);

        // Crear grados
        $grado1 = Grade::create([
            'name' => '1er Grado',
            'section' => 'A',
            'description' => 'Primer grado de primaria',
            'is_active' => true
        ]);

        $grado2 = Grade::create([
            'name' => '2do Grado',
            'section' => 'A',
            'description' => 'Segundo grado de primaria',
            'is_active' => true
        ]);

        $grado3 = Grade::create([
            'name' => '3er Grado',
            'section' => 'A',
            'description' => 'Tercer grado de primaria',
            'is_active' => true
        ]);

        // Crear cursos/materias
        $matematicas = Course::create([
            'name' => 'Matemáticas',
            'description' => 'Matemáticas básicas',
            'color' => '#EF4444', // Rojo
            'is_active' => true
        ]);

        $lenguaje = Course::create([
            'name' => 'Lenguaje',
            'description' => 'Comunicación y lenguaje',
            'color' => '#3B82F6', // Azul
            'is_active' => true
        ]);

        $ciencias = Course::create([
            'name' => 'Ciencias',
            'description' => 'Ciencias naturales',
            'color' => '#10B981', // Verde
            'is_active' => true
        ]);

        $historia = Course::create([
            'name' => 'Historia',
            'description' => 'Historia y geografía',
            'color' => '#F59E0B', // Amarillo
            'is_active' => true
        ]);

        $ingles = Course::create([
            'name' => 'Inglés',
            'description' => 'Idioma inglés',
            'color' => '#8B5CF6', // Púrpura
            'is_active' => true
        ]);

        // Crear profesores
        $profesor1 = User::create([
            'name' => 'profesor01',
            'first_name' => 'María',
            'last_name' => 'González',
            'email' => 'maria.gonzalez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $profesorRole->id,
        ]);

        $profesor2 = User::create([
            'name' => 'profesor02',
            'first_name' => 'Carlos',
            'last_name' => 'Rodríguez',
            'email' => 'carlos.rodriguez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $profesorRole->id,
        ]);

        $profesor3 = User::create([
            'name' => 'profesor03',
            'first_name' => 'Ana',
            'last_name' => 'Martínez',
            'email' => 'ana.martinez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $profesorRole->id,
        ]);

        $profesor4 = User::create([
            'name' => 'profesor04',
            'first_name' => 'Luis',
            'last_name' => 'Hernández',
            'email' => 'luis.hernandez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $profesorRole->id,
        ]);

        $profesor5 = User::create([
            'name' => 'profesor05',
            'first_name' => 'Patricia',
            'last_name' => 'López',
            'email' => 'patricia.lopez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $profesorRole->id,
        ]);

        // Crear padres
        $padre1 = User::create([
            'name' => 'padre01',
            'first_name' => 'Roberto',
            'last_name' => 'Pérez',
            'email' => 'roberto.perez@email.com',
            'password' => Hash::make('password'),
            'role_id' => $padreRole->id,
        ]);

        $padre2 = User::create([
            'name' => 'padre02',
            'first_name' => 'Carmen',
            'last_name' => 'García',
            'email' => 'carmen.garcia@email.com',
            'password' => Hash::make('password'),
            'role_id' => $padreRole->id,
        ]);

        $padre3 = User::create([
            'name' => 'padre03',
            'first_name' => 'Miguel',
            'last_name' => 'Torres',
            'email' => 'miguel.torres@email.com',
            'password' => Hash::make('password'),
            'role_id' => $padreRole->id,
        ]);

        // Crear estudiantes
        $estudiante1 = User::create([
            'name' => 'estudiante01',
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'email' => 'juan.perez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $estudianteRole->id,
            'parent_id' => $padre1->id,
            'grade_id' => $grado1->id,
        ]);

        $estudiante2 = User::create([
            'name' => 'estudiante02',
            'first_name' => 'Sofía',
            'last_name' => 'García',
            'email' => 'sofia.garcia@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $estudianteRole->id,
            'parent_id' => $padre2->id,
            'grade_id' => $grado1->id,
        ]);

        $estudiante3 = User::create([
            'name' => 'estudiante03',
            'first_name' => 'Diego',
            'last_name' => 'Torres',
            'email' => 'diego.torres@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $estudianteRole->id,
            'parent_id' => $padre3->id,
            'grade_id' => $grado2->id,
        ]);

        $estudiante4 = User::create([
            'name' => 'estudiante04',
            'first_name' => 'Valentina',
            'last_name' => 'Hernández',
            'email' => 'valentina.hernandez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $estudianteRole->id,
            'parent_id' => $padre1->id,
            'grade_id' => $grado2->id,
        ]);

        $estudiante5 = User::create([
            'name' => 'estudiante05',
            'first_name' => 'Andrés',
            'last_name' => 'López',
            'email' => 'andres.lopez@xchool.com',
            'password' => Hash::make('password'),
            'role_id' => $estudianteRole->id,
            'parent_id' => $padre2->id,
            'grade_id' => $grado3->id,
        ]);

        // Crear horarios
        // Lunes - 1er Grado
        Schedule::create([
            'grade_id' => $grado1->id,
            'course_id' => $matematicas->id,
            'teacher_id' => $profesor1->id,
            'day' => 'monday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Sala 101',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado1->id,
            'course_id' => $lenguaje->id,
            'teacher_id' => $profesor2->id,
            'day' => 'monday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Sala 101',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado1->id,
            'course_id' => $ciencias->id,
            'teacher_id' => $profesor3->id,
            'day' => 'monday',
            'start_time' => '10:30:00',
            'end_time' => '11:30:00',
            'room' => 'Laboratorio',
            'is_active' => true
        ]);

        // Martes - 1er Grado
        Schedule::create([
            'grade_id' => $grado1->id,
            'course_id' => $historia->id,
            'teacher_id' => $profesor4->id,
            'day' => 'tuesday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Sala 101',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado1->id,
            'course_id' => $ingles->id,
            'teacher_id' => $profesor5->id,
            'day' => 'tuesday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Sala 101',
            'is_active' => true
        ]);

        // Miércoles - 2do Grado
        Schedule::create([
            'grade_id' => $grado2->id,
            'course_id' => $matematicas->id,
            'teacher_id' => $profesor1->id,
            'day' => 'wednesday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Sala 102',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado2->id,
            'course_id' => $lenguaje->id,
            'teacher_id' => $profesor2->id,
            'day' => 'wednesday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Sala 102',
            'is_active' => true
        ]);

        // Jueves - 3er Grado
        Schedule::create([
            'grade_id' => $grado3->id,
            'course_id' => $ciencias->id,
            'teacher_id' => $profesor3->id,
            'day' => 'thursday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Laboratorio',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado3->id,
            'course_id' => $historia->id,
            'teacher_id' => $profesor4->id,
            'day' => 'thursday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Sala 103',
            'is_active' => true
        ]);

        // Viernes - Todos los grados
        Schedule::create([
            'grade_id' => $grado1->id,
            'course_id' => $ingles->id,
            'teacher_id' => $profesor5->id,
            'day' => 'friday',
            'start_time' => '08:00:00',
            'end_time' => '09:00:00',
            'room' => 'Sala 101',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado2->id,
            'course_id' => $ingles->id,
            'teacher_id' => $profesor5->id,
            'day' => 'friday',
            'start_time' => '09:00:00',
            'end_time' => '10:00:00',
            'room' => 'Sala 102',
            'is_active' => true
        ]);

        Schedule::create([
            'grade_id' => $grado3->id,
            'course_id' => $ingles->id,
            'teacher_id' => $profesor5->id,
            'day' => 'friday',
            'start_time' => '10:00:00',
            'end_time' => '11:00:00',
            'room' => 'Sala 103',
            'is_active' => true
        ]);

        echo "✅ Seeder ejecutado exitosamente!\n";
        echo "📚 Se crearon:\n";
        echo "   - 1 Usuario Colegio (admin)\n";
        echo "   - 5 Profesores\n";
        echo "   - 3 Padres\n";
        echo "   - 5 Estudiantes\n";
        echo "   - 3 Grados\n";
        echo "   - 5 Cursos/Materias\n";
        echo "   - 12 Horarios\n";
        echo "\n🔑 Credenciales de acceso:\n";
        echo "   - Colegio: ronnycorreaunity@gmail.com / password\n";
        echo "   - Profesores: profesor01@xchool.com / password\n";
        echo "   - Estudiantes: estudiante01@xchool.com / password\n";
        echo "   - Padres: padre01@email.com / password\n";
    }
}
