<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserManagementController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentManagementController;

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
});

// Habilitar solo las rutas de autenticación necesarias (login, logout, etc.)
require __DIR__.'/auth.php';
