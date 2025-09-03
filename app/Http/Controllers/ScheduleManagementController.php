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
    }

    public function index(Request $request)
    {
        $user = $request->user();
        
        // Si es colegio, ve todo
        if ($user->hasRole('colegio')) {
            $grades = Grade::withCount('students')->active()->get();
            $courses = Course::withCount('teachers')->active()->get();
            $teachers = User::whereHas('role', function($query) {
                $query->where('name', 'profesor');
            })->get(['id', 'first_name', 'last_name', 'name']);

            return Inertia::render('ScheduleManagement/Index', [
                'grades' => $grades,
                'courses' => $courses,
                'teachers' => $teachers,
                'userRole' => 'colegio',
            ]);
        }
        
        // Si es profesor, ve solo sus horarios
        if ($user->hasRole('profesor')) {
            $mySchedules = $user->teachingSchedules()
                ->with(['grade', 'course'])
                ->active()
                ->orderBy('day')
                ->orderBy('start_time')
                ->get()
                ->groupBy('day');

            $myGrades = $user->teachingSchedules()
                ->with('grade')
                ->active()
                ->get()
                ->pluck('grade')
                ->unique('id')
                ->values();

            $myCourses = $user->teachingSchedules()
                ->with('course')
                ->active()
                ->get()
                ->pluck('course')
                ->unique('id')
                ->values();

            return Inertia::render('ScheduleManagement/TeacherView', [
                'mySchedules' => $mySchedules,
                'myGrades' => $myGrades,
                'myCourses' => $myCourses,
                'userRole' => 'profesor',
                'teacher' => $user,
            ]);
        }

        // Otros roles no tienen acceso
        abort(403, 'No tienes permisos para acceder a esta sección.');
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

    /**
     * Mostrar horario del estudiante
     */
    public function studentSchedule(Request $request)
    {
        $user = $request->user();
        
        // Obtener el grado del estudiante
        $grade = $user->grade;
        
        if (!$grade) {
            return Inertia::render('Student/Schedule', [
                'schedules' => collect(),
                'grade' => null,
                'message' => 'No tienes un grado asignado. Contacta al administrador.'
            ]);
        }

        // Obtener horarios del grado del estudiante
        $schedules = Schedule::where('grade_id', $grade->id)
            ->with(['course', 'teacher'])
            ->active()
            ->orderBy('day')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day');

        return Inertia::render('Student/Schedule', [
            'schedules' => $schedules,
            'grade' => $grade,
            'user' => $user
        ]);
    }

    /**
     * Mostrar cursos del estudiante
     */
    public function studentCourses(Request $request)
    {
        $user = $request->user();
        $grade = $user->grade;
        
        if (!$grade) {
            return Inertia::render('Student/Courses', [
                'courses' => collect(),
                'grade' => null,
                'message' => 'No tienes un grado asignado. Contacta al administrador.'
            ]);
        }

        // Obtener cursos únicos del grado del estudiante
        $courses = Schedule::where('grade_id', $grade->id)
            ->with(['course', 'teacher'])
            ->active()
            ->get()
            ->groupBy('course_id')
            ->map(function ($schedules) {
                $firstSchedule = $schedules->first();
                return [
                    'course' => $firstSchedule->course,
                    'teacher' => $firstSchedule->teacher,
                    'total_hours' => $schedules->count(),
                    'schedules' => $schedules
                ];
            });

        return Inertia::render('Student/Courses', [
            'courses' => $courses,
            'grade' => $grade,
            'user' => $user
        ]);
    }

    /**
     * Mostrar profesores del estudiante
     */
    public function studentTeachers(Request $request)
    {
        $user = $request->user();
        $grade = $user->grade;
        
        if (!$grade) {
            return Inertia::render('Student/Teachers', [
                'teachers' => collect(),
                'grade' => null,
                'message' => 'No tienes un grado asignado. Contacta al administrador.'
            ]);
        }

        // Obtener profesores únicos del grado del estudiante
        $teachers = Schedule::where('grade_id', $grade->id)
            ->with(['teacher', 'course'])
            ->active()
            ->get()
            ->groupBy('teacher_id')
            ->map(function ($schedules) {
                $firstSchedule = $schedules->first();
                return [
                    'teacher' => $firstSchedule->teacher,
                    'courses' => $schedules->pluck('course')->unique('id'),
                    'total_classes' => $schedules->count(),
                    'schedules' => $schedules
                ];
            });

        return Inertia::render('Student/Teachers', [
            'teachers' => $teachers,
            'grade' => $grade,
            'user' => $user
        ]);
    }

    // ==================== MÉTODOS PARA PADRES ====================
    
    /**
     * Mostrar información del hijo del padre
     */
    public function parentChild(Request $request)
    {
        $user = $request->user();
        
        // Obtener el primer hijo del padre
        $child = $user->students()->first();
        
        if (!$child) {
            $message = "No tienes un hijo asignado en el sistema.";
            return Inertia::render('Parent/Child', [
                'child' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        $grade = $child->grade;
        
        return Inertia::render('Parent/Child', [
            'child' => $child,
            'grade' => $grade,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar horario del hijo del padre
     */
    public function parentChildSchedule(Request $request)
    {
        $user = $request->user();
        $child = $user->students()->first();
        
        if (!$child) {
            $message = "No tienes un hijo asignado en el sistema.";
            return Inertia::render('Parent/ChildSchedule', [
                'schedules' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        $grade = $child->grade;
        
        if (!$grade) {
            $message = "Tu hijo no tiene un grado asignado.";
            return Inertia::render('Parent/ChildSchedule', [
                'schedules' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        // Obtener horarios del hijo (mismo código que para estudiantes)
        $schedules = Schedule::where('grade_id', $grade->id)
            ->with(['course', 'teacher'])
            ->active()
            ->get()
            ->groupBy('day');
        
        // Convertir a formato legible
        $schedulesData = [];
        foreach ($schedules as $day => $daySchedules) {
            $dayName = $this->getDayName($day);
            $schedulesData[$dayName] = $daySchedules->map(function ($schedule) {
                return [
                    'id' => $schedule->id,
                    'course' => [
                        'id' => $schedule->course->id,
                        'name' => $schedule->course->name,
                    ],
                    'teacher' => [
                        'id' => $schedule->teacher->id,
                        'name' => $schedule->teacher->name,
                    ],
                    'start_time' => $schedule->start_time,
                    'end_time' => $schedule->end_time,
                    'day' => $schedule->day,
                ];
            });
        }
        
        return Inertia::render('Parent/ChildSchedule', [
            'schedules' => $schedulesData,
            'grade' => $grade,
            'child' => $child,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar profesores del hijo del padre
     */
    public function parentChildTeachers(Request $request)
    {
        $user = $request->user();
        $child = $user->students()->first();
        
        if (!$child) {
            $message = "No tienes un hijo asignado en el sistema.";
            return Inertia::render('Parent/ChildTeachers', [
                'teachers' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        $grade = $child->grade;
        
        if (!$grade) {
            $message = "Tu hijo no tiene un grado asignado.";
            return Inertia::render('Parent/ChildTeachers', [
                'teachers' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        // Obtener profesores del hijo (mismo código que para estudiantes)
        $schedules = Schedule::where('grade_id', $grade->id)
            ->with(['course', 'teacher'])
            ->active()
            ->get();
        
        $teachersData = [];
        foreach ($schedules as $schedule) {
            $teacherId = $schedule->teacher->id;
            
            if (!isset($teachersData[$teacherId])) {
                $teachersData[$teacherId] = [
                    'teacher' => [
                        'id' => $schedule->teacher->id,
                        'name' => $schedule->teacher->name,
                        'email' => $schedule->teacher->email,
                    ],
                    'courses' => [],
                    'schedules' => [],
                    'total_classes' => 0,
                ];
            }
            
            // Agregar curso si no existe
            $courseExists = false;
            foreach ($teachersData[$teacherId]['courses'] as $course) {
                if ($course['id'] === $schedule->course->id) {
                    $courseExists = true;
                    break;
                }
            }
            
            if (!$courseExists) {
                $teachersData[$teacherId]['courses'][] = [
                    'id' => $schedule->course->id,
                    'name' => $schedule->course->name,
                ];
            }
            
            // Agregar horario
            $teachersData[$teacherId]['schedules'][] = [
                'id' => $schedule->id,
                'day' => $schedule->day,
                'start_time' => $schedule->start_time,
                'end_time' => $schedule->end_time,
                'course' => [
                    'id' => $schedule->course->id,
                    'name' => $schedule->course->name,
                ],
            ];
            
            $teachersData[$teacherId]['total_classes']++;
        }
        
        return Inertia::render('Parent/ChildTeachers', [
            'teachers' => $teachersData,
            'grade' => $grade,
            'child' => $child,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar asistencia del hijo del padre
     */
    public function parentChildAttendance(Request $request)
    {
        $user = $request->user();
        $child = $user->students()->first();
        
        if (!$child) {
            $message = "No tienes un hijo asignado en el sistema.";
            return Inertia::render('Parent/ChildAttendance', [
                'attendance' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        $grade = $child->grade;
        
        // Por ahora, datos de ejemplo para asistencia
        $attendanceData = [
            'total_days' => 20,
            'present_days' => 18,
            'absent_days' => 2,
            'attendance_percentage' => 90.0,
            'recent_attendance' => [
                ['date' => '2024-01-15', 'status' => 'present', 'course' => 'Matemáticas'],
                ['date' => '2024-01-14', 'status' => 'present', 'course' => 'Lenguaje'],
                ['date' => '2024-01-13', 'status' => 'absent', 'course' => 'Ciencias'],
                ['date' => '2024-01-12', 'status' => 'present', 'course' => 'Historia'],
                ['date' => '2024-01-11', 'status' => 'present', 'course' => 'Educación Física'],
            ]
        ];
        
        return Inertia::render('Parent/ChildAttendance', [
            'attendance' => $attendanceData,
            'grade' => $grade,
            'child' => $child,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar calificaciones del hijo del padre
     */
    public function parentChildGrades(Request $request)
    {
        $user = $request->user();
        $child = $user->students()->first();
        
        if (!$child) {
            $message = "No tienes un hijo asignado en el sistema.";
            return Inertia::render('Parent/ChildGrades', [
                'grades' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        $grade = $child->grade;
        
        // Por ahora, datos de ejemplo para calificaciones
        $gradesData = [
            'overall_average' => 85.5,
            'courses' => [
                [
                    'course' => 'Matemáticas',
                    'teacher' => 'Prof. García',
                    'average' => 88.0,
                    'grades' => [
                        ['assignment' => 'Examen Parcial', 'grade' => 90, 'date' => '2024-01-10'],
                        ['assignment' => 'Tarea 1', 'grade' => 85, 'date' => '2024-01-08'],
                        ['assignment' => 'Proyecto', 'grade' => 89, 'date' => '2024-01-05'],
                    ]
                ],
                [
                    'course' => 'Lenguaje',
                    'teacher' => 'Prof. López',
                    'average' => 82.0,
                    'grades' => [
                        ['assignment' => 'Ensayo', 'grade' => 80, 'date' => '2024-01-12'],
                        ['assignment' => 'Lectura', 'grade' => 85, 'date' => '2024-01-09'],
                        ['assignment' => 'Gramática', 'grade' => 81, 'date' => '2024-01-06'],
                    ]
                ],
                [
                    'course' => 'Ciencias',
                    'teacher' => 'Prof. Martínez',
                    'average' => 87.0,
                    'grades' => [
                        ['assignment' => 'Laboratorio', 'grade' => 90, 'date' => '2024-01-11'],
                        ['assignment' => 'Examen', 'grade' => 85, 'date' => '2024-01-07'],
                        ['assignment' => 'Proyecto', 'grade' => 86, 'date' => '2024-01-04'],
                    ]
                ],
            ]
        ];
        
        return Inertia::render('Parent/ChildGrades', [
            'grades' => $gradesData,
            'grade' => $grade,
            'child' => $child,
            'user' => $user
        ]);
    }
    
    /**
     * Mostrar reportes del hijo del padre
     */
    public function parentChildReports(Request $request)
    {
        $user = $request->user();
        $child = $user->students()->first();
        
        if (!$child) {
            $message = "No tienes un hijo asignado en el sistema.";
            return Inertia::render('Parent/ChildReports', [
                'reports' => [],
                'grade' => null,
                'user' => $user,
                'message' => $message
            ]);
        }
        
        $grade = $child->grade;
        
        // Por ahora, datos de ejemplo para reportes
        $reportsData = [
            'academic_progress' => [
                'overall_performance' => 'Bueno',
                'strengths' => ['Matemáticas', 'Participación en clase'],
                'areas_for_improvement' => ['Lectura comprensiva', 'Organización'],
                'recommendations' => 'Continuar con el buen desempeño en matemáticas y trabajar en mejorar la comprensión lectora.'
            ],
            'behavioral_notes' => [
                ['date' => '2024-01-15', 'note' => 'Excelente participación en clase de ciencias', 'type' => 'positive'],
                ['date' => '2024-01-12', 'note' => 'Ayudó a un compañero con la tarea de matemáticas', 'type' => 'positive'],
                ['date' => '2024-01-10', 'note' => 'Necesita mejorar la puntualidad en las entregas', 'type' => 'improvement'],
            ],
            'upcoming_events' => [
                ['date' => '2024-01-20', 'event' => 'Examen de Matemáticas', 'type' => 'exam'],
                ['date' => '2024-01-25', 'event' => 'Presentación de Proyecto de Ciencias', 'type' => 'presentation'],
                ['date' => '2024-01-30', 'event' => 'Reunión de Padres', 'type' => 'meeting'],
            ]
        ];
        
        return Inertia::render('Parent/ChildReports', [
            'reports' => $reportsData,
            'grade' => $grade,
            'child' => $child,
            'user' => $user
        ]);
    }

    /**
     * Convertir número de día a nombre
     */
    private function getDayName($dayNumber)
    {
        $days = [
            1 => 'Lunes',
            2 => 'Martes', 
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo'
        ];

        return $days[$dayNumber] ?? 'Día ' . $dayNumber;
    }
}
