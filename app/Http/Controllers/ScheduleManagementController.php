<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Controllers\Controller;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ScheduleManagementController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!$request->user()->hasRole('colegio')) {
                abort(403, 'Acceso denegado. Solo usuarios con rol Colegio pueden acceder.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $grades = Grade::withCount('students')->active()->get();
        $courses = Course::withCount('teachers')->active()->get();
        $teachers = User::whereHas('role', function($query) {
            $query->where('name', 'profesor');
        })->get(['id', 'first_name', 'last_name', 'name']);

        return Inertia::render('ScheduleManagement/Index', [
            'grades' => $grades,
            'courses' => $courses,
            'teachers' => $teachers,
        ]);
    }

    // Gestión de Grados
    public function storeGrade(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:10',
            'description' => 'nullable|string',
        ]);

        Grade::create($request->all());

        return redirect()->back()->with('success', 'Grado creado exitosamente.');
    }

    public function updateGrade(Request $request, Grade $grade)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'section' => 'nullable|string|max:10',
            'description' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $grade->update($request->all());

        return redirect()->back()->with('success', 'Grado actualizado exitosamente.');
    }

    public function destroyGrade(Grade $grade)
    {
        if ($grade->students()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar un grado que tiene estudiantes asignados.');
        }

        $grade->delete();

        return redirect()->back()->with('success', 'Grado eliminado exitosamente.');
    }

    // Gestión de Cursos
    public function storeCourse(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:courses',
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
        ]);

        Course::create($request->all());

        return redirect()->back()->with('success', 'Curso creado exitosamente.');
    }

    public function updateCourse(Request $request, Course $course)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('courses')->ignore($course->id)],
            'description' => 'nullable|string',
            'color' => 'required|string|max:7',
            'is_active' => 'boolean',
        ]);

        $course->update($request->all());

        return redirect()->back()->with('success', 'Curso actualizado exitosamente.');
    }

    public function destroyCourse(Course $course)
    {
        if ($course->schedules()->count() > 0) {
            return redirect()->back()->with('error', 'No se puede eliminar un curso que tiene horarios asignados.');
        }

        $course->delete();

        return redirect()->back()->with('success', 'Curso eliminado exitosamente.');
    }

    // Gestión de Horarios
    public function storeSchedule(Request $request)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
        ]);

        // Verificar que el profesor no tenga conflicto de horario
        $conflict = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'El profesor tiene un conflicto de horario en este día y hora.');
        }

        Schedule::create($request->all());

        return redirect()->back()->with('success', 'Horario creado exitosamente.');
    }

    public function updateSchedule(Request $request, Schedule $schedule)
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'course_id' => 'required|exists:courses,id',
            'teacher_id' => 'required|exists:users,id',
            'day' => 'required|in:monday,tuesday,wednesday,thursday,friday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'room' => 'nullable|string|max:255',
            'is_active' => 'boolean',
        ]);

        // Verificar conflicto de horario (excluyendo el horario actual)
        $conflict = Schedule::where('teacher_id', $request->teacher_id)
            ->where('day', $request->day)
            ->where('id', '!=', $schedule->id)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time])
                      ->orWhere(function($q) use ($request) {
                          $q->where('start_time', '<=', $request->start_time)
                            ->where('end_time', '>=', $request->end_time);
                      });
            })
            ->exists();

        if ($conflict) {
            return redirect()->back()->with('error', 'El profesor tiene un conflicto de horario en este día y hora.');
        }

        $schedule->update($request->all());

        return redirect()->back()->with('success', 'Horario actualizado exitosamente.');
    }

    public function destroySchedule(Schedule $schedule)
    {
        $schedule->delete();

        return redirect()->back()->with('success', 'Horario eliminado exitosamente.');
    }

    // Obtener horarios por grado
    public function getSchedulesByGrade(Grade $grade)
    {
        $schedules = $grade->schedules()
            ->with(['course', 'teacher'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return response()->json($schedules);
    }

    // Obtener horarios por profesor
    public function getSchedulesByTeacher(User $teacher)
    {
        if (!$teacher->hasRole('profesor')) {
            abort(400, 'El usuario no es un profesor.');
        }

        $schedules = $teacher->teachingSchedules()
            ->with(['grade', 'course'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return response()->json($schedules);
    }

    // Obtener horarios por estudiante
    public function getSchedulesByStudent(User $student)
    {
        if (!$student->hasRole('estudiante')) {
            abort(400, 'El usuario no es un estudiante.');
        }

        if (!$student->grade_id) {
            return response()->json([]);
        }

        $schedules = $student->schedules()
            ->with(['course', 'teacher'])
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return response()->json($schedules);
    }
}
