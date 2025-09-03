<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class StudentManagementController extends Controller
{
    /**
     * Display the student management page (only for colegio role).
     */
    public function index(Request $request): Response
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado. Solo los colegios pueden gestionar estudiantes.');
        }

        $students = User::with(['role', 'parent'])
            ->whereHas('role', function($query) {
                $query->where('name', 'estudiante');
            })
            ->get();

        $parents = User::with('role')
            ->whereHas('role', function($query) {
                $query->where('name', 'padre');
            })
            ->get();

        return Inertia::render('StudentManagement/Index', [
            'students' => $students,
            'parents' => $parents,
        ]);
    }

    /**
     * Create a new student.
     */
    public function store(Request $request)
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'parent_id' => 'required|exists:users,id',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:masculino,femenino,otro',
        ]);

        // Obtener el rol de estudiante
        $studentRole = Role::where('name', 'estudiante')->firstOrFail();

        // Generar username automáticamente
        $username = $this->generateStudentUsername();

        // Generar contraseña temporal
        $password = Str::random(8);

        // Crear el estudiante
        $student = User::create([
            'name' => $username,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'password' => Hash::make($password),
            'role_id' => $studentRole->id,
            'parent_id' => $request->parent_id,
        ]);

        return redirect()->back()->with('success', 'Estudiante creado exitosamente.');
    }

    /**
     * Update student information.
     */
    public function update(Request $request, User $student)
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $student->id,
            'parent_id' => 'required|exists:users,id',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:masculino,femenino,otro',
        ]);

        $student->update($request->only([
            'first_name', 'last_name', 'email', 'parent_id', 'birth_date', 'gender'
        ]));

        return redirect()->back()->with('success', 'Estudiante actualizado exitosamente.');
    }

    /**
     * Delete student.
     */
    public function destroy(User $student)
    {
        // Verificar que el usuario sea colegio
        if (!auth()->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $student->delete();

        return redirect()->back()->with('success', 'Estudiante eliminado exitosamente.');
    }

    /**
     * Search parents for student assignment.
     */
    public function searchParents(Request $request)
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $search = $request->get('search', '');
        
        $parents = User::with('role')
            ->whereHas('role', function($query) {
                $query->where('name', 'padre');
            })
            ->where(function($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
            })
            ->limit(10)
            ->get()
            ->map(function($parent) {
                return [
                    'id' => $parent->id,
                    'name' => $parent->name,
                    'first_name' => $parent->first_name,
                    'last_name' => $parent->last_name,
                    'email' => $parent->email,
                    'display_name' => $parent->display_name,
                    'avatar_initial' => strtoupper(substr($parent->first_name ?? $parent->name, 0, 1)),
                ];
            });

        return response()->json($parents);
    }

    /**
     * Generate unique student username.
     */
    private function generateStudentUsername(): string
    {
        $prefix = 'estudiante';
        $counter = 1;
        
        do {
            $username = $prefix . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $exists = User::where('name', $username)->exists();
            $counter++;
        } while ($exists);

        return $username;
    }
}
