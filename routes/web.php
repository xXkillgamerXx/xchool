<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentManagementController;
use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\ScheduleManagementController;

use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => false, // Deshabilitar registro público
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// Rutas para gestión de usuarios (solo colegios)
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/user-management', [UserManagementController::class, 'index'])
        ->name('user-management.index');
    Route::post('/user-management/invite', [UserManagementController::class, 'sendInvitation'])
        ->name('user-management.invite');
    Route::delete('/user-management/invitation/{invitation}', [UserManagementController::class, 'deleteInvitation'])
        ->name('user-management.delete-invitation');
    Route::delete('/user-management/user/{user}', [UserManagementController::class, 'deleteUser'])
        ->name('user-management.delete-user');
});

// Ruta para aceptar invitaciones (pública)
Route::get('/invitation/{token}', [UserManagementController::class, 'acceptInvitation'])
    ->name('invitation.accept');

Route::middleware('auth')->group(function () {
    // Rutas del perfil
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rutas de gestión de estudiantes (solo colegios)
    Route::resource('students', StudentManagementController::class)->except(['show']);
    Route::get('/students/search-parents', [StudentManagementController::class, 'searchParents'])->name('students.search-parents');

    // Rutas del historial de actividades (solo colegios)
    Route::get('/activity-log', [ActivityLogController::class, 'index'])->name('activity-log.index');
    Route::get('/activity-log/{model_type}/{model_id}', [ActivityLogController::class, 'forModel'])->name('activity-log.model');

    // Rutas de gestión de horarios (solo colegios)
    Route::get('/schedule-management', [ScheduleManagementController::class, 'index'])->name('schedule-management.index');
    Route::post('/schedule-management/grades', [ScheduleManagementController::class, 'storeGrade'])->name('schedule-management.grades.store');
    Route::put('/schedule-management/grades/{grade}', [ScheduleManagementController::class, 'updateGrade'])->name('schedule-management.grades.update');
    Route::delete('/schedule-management/grades/{grade}', [ScheduleManagementController::class, 'destroyGrade'])->name('schedule-management.grades.destroy');
    
    Route::post('/schedule-management/courses', [ScheduleManagementController::class, 'storeCourse'])->name('schedule-management.courses.store');
    Route::put('/schedule-management/courses/{course}', [ScheduleManagementController::class, 'updateCourse'])->name('schedule-management.courses.update');
    Route::delete('/schedule-management/courses/{course}', [ScheduleManagementController::class, 'destroyCourse'])->name('schedule-management.courses.destroy');
    
    Route::post('/schedule-management/schedules', [ScheduleManagementController::class, 'storeSchedule'])->name('schedule-management.schedules.store');
    Route::put('/schedule-management/schedules/{schedule}', [ScheduleManagementController::class, 'updateSchedule'])->name('schedule-management.schedules.update');
    Route::delete('/schedule-management/schedules/{schedule}', [ScheduleManagementController::class, 'destroySchedule'])->name('schedule-management.schedules.destroy');
    
    // Rutas para obtener horarios
    Route::get('/schedule-management/grades/{grade}/schedules', [ScheduleManagementController::class, 'getSchedulesByGrade'])->name('schedule-management.grades.schedules');
    Route::get('/schedule-management/teachers/{teacher}/schedules', [ScheduleManagementController::class, 'getSchedulesByTeacher'])->name('schedule-management.teachers.schedules');
    Route::get('/schedule-management/students/{student}/schedules', [ScheduleManagementController::class, 'getSchedulesByStudent'])->name('schedule-management.students.schedules');
});

// Habilitar solo las rutas de autenticación necesarias (login, logout, etc.)
require __DIR__.'/auth.php';
