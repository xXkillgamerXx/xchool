<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\StudentGrade;
use App\Models\Course;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class GradeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la página de asignación de calificaciones para profesores
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden acceder a esta sección.');
        }

        // Obtener calificaciones recientes del profesor
        $recentGrades = $user->teacherGrades()
            ->with(['student', 'course'])
            ->orderBy('grade_date', 'desc')
            ->limit(10)
            ->get();

        // Obtener cursos que enseña el profesor
        $myCourses = $user->teachingSchedules()
            ->with('course')
            ->active()
            ->get()
            ->pluck('course')
            ->unique('id')
            ->values();

        // Obtener grados que enseña el profesor
        $myGrades = $user->teachingSchedules()
            ->with('grade')
            ->active()
            ->get()
            ->pluck('grade')
            ->unique('id')
            ->values();

        return Inertia::render('Grades/Index', [
            'recentGrades' => $recentGrades,
            'myCourses' => $myCourses,
            'myGrades' => $myGrades,
            'teacher' => $user,
        ]);
    }

    /**
     * Mostrar el formulario para asignar calificaciones
     */
    public function create(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden asignar calificaciones.');
        }

        // Obtener cursos y grados que enseña el profesor
        $myCourses = $user->teachingSchedules()
            ->with('course')
            ->active()
            ->get()
            ->pluck('course')
            ->unique('id')
            ->values();

        $myGrades = $user->teachingSchedules()
            ->with('grade')
            ->active()
            ->get()
            ->pluck('grade')
            ->unique('id')
            ->values();

        return Inertia::render('Grades/Create', [
            'myCourses' => $myCourses,
            'myGrades' => $myGrades,
            'teacher' => $user,
        ]);
    }

    /**
     * Obtener estudiantes de un grado específico
     */
    public function getStudents(Request $request, $gradeId)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden acceder a esta información.');
        }

        // Verificar que el profesor tenga acceso a este grado
        $hasAccess = $user->teachingSchedules()
            ->where('grade_id', $gradeId)
            ->active()
            ->exists();

        if (!$hasAccess) {
            abort(403, 'No tienes acceso a este grado.');
        }

        $students = User::whereHas('role', function($query) {
            $query->where('name', 'estudiante');
        })
        ->where('grade_id', $gradeId)
        ->with(['parent'])
        ->get()
        ->map(function($student) {
            return [
                'id' => $student->id,
                'name' => $student->name,
                'first_name' => $student->first_name,
                'last_name' => $student->last_name,
                'email' => $student->email,
                'parent' => $student->parent ? [
                    'id' => $student->parent->id,
                    'name' => $student->parent->name,
                    'email' => $student->parent->email,
                ] : null,
            ];
        });

        return response()->json($students);
    }

    /**
     * Guardar las calificaciones asignadas
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden asignar calificaciones.');
        }

        $request->validate([
            'course_id' => 'required|exists:courses,id',
            'assignment_name' => 'required|string|max:255',
            'type' => 'required|in:exam,homework,project,quiz,participation,other',
            'max_score' => 'required|numeric|min:1|max:1000',
            'grade_date' => 'required|date',
            'grades' => 'required|array',
            'grades.*.student_id' => 'required|exists:users,id',
            'grades.*.score' => 'required|numeric|min:0|max:1000',
            'grades.*.comments' => 'nullable|string|max:500',
        ]);

        // Verificar que el profesor tenga acceso a este curso
        $hasAccess = $user->teachingSchedules()
            ->where('course_id', $request->course_id)
            ->active()
            ->exists();

        if (!$hasAccess) {
            abort(403, 'No tienes acceso a este curso.');
        }

        DB::beginTransaction();
        
        try {
            // Crear calificaciones
            foreach ($request->grades as $gradeData) {
                StudentGrade::create([
                    'student_id' => $gradeData['student_id'],
                    'course_id' => $request->course_id,
                    'teacher_id' => $user->id,
                    'assignment_name' => $request->assignment_name,
                    'type' => $request->type,
                    'score' => $gradeData['score'],
                    'max_score' => $request->max_score,
                    'comments' => $gradeData['comments'] ?? null,
                    'grade_date' => $request->grade_date,
                ]);
            }

            DB::commit();

            return redirect()->route('grades.index')
                ->with('success', 'Calificaciones asignadas exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al asignar las calificaciones. Inténtalo de nuevo.');
        }
    }

    /**
     * Ver calificaciones de un estudiante específico
     */
    public function studentGrades(Request $request, User $student)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden ver calificaciones de estudiantes.');
        }

        // Verificar que el profesor tenga estudiantes en el mismo grado
        $teacherGrades = $user->teachingSchedules()
            ->with('grade')
            ->active()
            ->get()
            ->pluck('grade.id')
            ->unique();

        if (!in_array($student->grade_id, $teacherGrades->toArray())) {
            abort(403, 'No tienes acceso a este estudiante.');
        }

        $grades = StudentGrade::where('student_id', $student->id)
            ->with(['course', 'teacher'])
            ->orderBy('grade_date', 'desc')
            ->paginate(20);

        return Inertia::render('Grades/StudentGrades', [
            'student' => $student,
            'grades' => $grades,
        ]);
    }

    /**
     * Ver estadísticas de calificaciones
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden ver estadísticas de calificaciones.');
        }

        // Obtener estadísticas por curso
        $statistics = [];
        $myCourses = $user->teachingSchedules()
            ->with('course')
            ->active()
            ->get()
            ->pluck('course')
            ->unique('id')
            ->values();

        foreach ($myCourses as $course) {
            $totalGrades = StudentGrade::where('course_id', $course->id)
                ->where('teacher_id', $user->id)
                ->count();

            $averageScore = StudentGrade::where('course_id', $course->id)
                ->where('teacher_id', $user->id)
                ->avg('score');

            $averageMaxScore = StudentGrade::where('course_id', $course->id)
                ->where('teacher_id', $user->id)
                ->avg('max_score');

            $averagePercentage = $averageMaxScore > 0 ? round(($averageScore / $averageMaxScore) * 100, 2) : 0;

            $statistics[$course->id] = [
                'course' => $course,
                'total_grades' => $totalGrades,
                'average_score' => $averageScore ? round($averageScore, 2) : 0,
                'average_percentage' => $averagePercentage,
            ];
        }

        return Inertia::render('Grades/Statistics', [
            'statistics' => $statistics,
            'teacher' => $user,
        ]);
    }
}
