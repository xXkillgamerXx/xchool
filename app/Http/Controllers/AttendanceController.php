<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Attendance;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Mostrar la página de registro de asistencia para profesores
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden acceder a esta sección.');
        }

        // Obtener horarios del profesor
        $schedules = $user->teachingSchedules()
            ->with(['grade', 'course'])
            ->active()
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        // Obtener estudiantes por grado
        $studentsByGrade = [];
        $myGrades = $user->teachingSchedules()
            ->with('grade')
            ->active()
            ->get()
            ->pluck('grade')
            ->unique('id')
            ->values();

        foreach ($myGrades as $grade) {
            $students = User::whereHas('role', function($query) {
                $query->where('name', 'estudiante');
            })
            ->where('grade_id', $grade->id)
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

            $studentsByGrade[$grade->id] = [
                'grade' => $grade,
                'students' => $students,
                'total_students' => $students->count(),
            ];
        }

        return Inertia::render('Attendance/Index', [
            'schedules' => $schedules,
            'studentsByGrade' => $studentsByGrade,
            'teacher' => $user,
        ]);
    }

    /**
     * Mostrar el formulario de registro de asistencia para una clase específica
     */
    public function create(Request $request, Schedule $schedule)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden acceder a esta sección.');
        }

        // Verificar que el profesor tenga acceso a este horario
        if ($schedule->teacher_id !== $user->id) {
            abort(403, 'No tienes acceso a este horario.');
        }

        // Obtener estudiantes del grado
        $students = User::whereHas('role', function($query) {
            $query->where('name', 'estudiante');
        })
        ->where('grade_id', $schedule->grade_id)
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

        // Obtener asistencia existente para hoy si existe
        $today = Carbon::today();
        $existingAttendance = Attendance::where('schedule_id', $schedule->id)
            ->where('attendance_date', $today)
            ->get()
            ->keyBy('student_id');

        return Inertia::render('Attendance/Create', [
            'schedule' => $schedule->load(['grade', 'course']),
            'students' => $students,
            'existingAttendance' => $existingAttendance,
            'attendanceDate' => $today->format('Y-m-d'),
        ]);
    }

    /**
     * Guardar el registro de asistencia
     */
    public function store(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden registrar asistencia.');
        }

        $request->validate([
            'schedule_id' => 'required|exists:schedules,id',
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.student_id' => 'required|exists:users,id',
            'attendance.*.status' => 'required|in:present,absent,late,excused',
            'attendance.*.notes' => 'nullable|string|max:500',
        ]);

        $schedule = Schedule::findOrFail($request->schedule_id);
        
        // Verificar que el profesor tenga acceso a este horario
        if ($schedule->teacher_id !== $user->id) {
            abort(403, 'No tienes acceso a este horario.');
        }

        DB::beginTransaction();
        
        try {
            // Eliminar registros existentes para esta fecha y horario
            Attendance::where('schedule_id', $request->schedule_id)
                ->where('attendance_date', $request->attendance_date)
                ->delete();

            // Crear nuevos registros de asistencia
            foreach ($request->attendance as $attendanceData) {
                Attendance::create([
                    'student_id' => $attendanceData['student_id'],
                    'schedule_id' => $request->schedule_id,
                    'teacher_id' => $user->id,
                    'attendance_date' => $request->attendance_date,
                    'status' => $attendanceData['status'],
                    'notes' => $attendanceData['notes'] ?? null,
                ]);
            }

            DB::commit();

            return redirect()->route('attendance.index')
                ->with('success', 'Asistencia registrada exitosamente.');

        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()
                ->with('error', 'Error al registrar la asistencia. Inténtalo de nuevo.');
        }
    }

    /**
     * Obtener historial de asistencia de un estudiante
     */
    public function studentHistory(Request $request, User $student)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden ver el historial de asistencia.');
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

        $attendances = Attendance::where('student_id', $student->id)
            ->with(['schedule.course', 'schedule.grade'])
            ->orderBy('attendance_date', 'desc')
            ->paginate(20);

        return Inertia::render('Attendance/StudentHistory', [
            'student' => $student,
            'attendances' => $attendances,
        ]);
    }

    /**
     * Obtener estadísticas de asistencia
     */
    public function statistics(Request $request)
    {
        $user = $request->user();
        
        if (!$user->hasRole('profesor')) {
            abort(403, 'Solo los profesores pueden ver estadísticas de asistencia.');
        }

        // Obtener estadísticas por grado
        $statistics = [];
        $myGrades = $user->teachingSchedules()
            ->with('grade')
            ->active()
            ->get()
            ->pluck('grade')
            ->unique('id')
            ->values();

        foreach ($myGrades as $grade) {
            $totalStudents = User::whereHas('role', function($query) {
                $query->where('name', 'estudiante');
            })->where('grade_id', $grade->id)->count();

            $totalAttendance = Attendance::whereHas('schedule', function($query) use ($user, $grade) {
                $query->where('teacher_id', $user->id)
                      ->where('grade_id', $grade->id);
            })->count();

            $presentCount = Attendance::whereHas('schedule', function($query) use ($user, $grade) {
                $query->where('teacher_id', $user->id)
                      ->where('grade_id', $grade->id);
            })->where('status', 'present')->count();

            $statistics[$grade->id] = [
                'grade' => $grade,
                'total_students' => $totalStudents,
                'total_attendance' => $totalAttendance,
                'present_count' => $presentCount,
                'attendance_percentage' => $totalAttendance > 0 ? round(($presentCount / $totalAttendance) * 100, 2) : 0,
            ];
        }

        return Inertia::render('Attendance/Statistics', [
            'statistics' => $statistics,
            'teacher' => $user,
        ]);
    }
}
