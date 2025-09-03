<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\LogsActivity;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'role_id',
        'parent_id',
        'grade_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the role that owns the user.
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Check if user has a specific role
     */
    public function hasRole($role): bool
    {
        return $this->role->name === $role;
    }

    /**
     * Check if user is a colegio
     */
    public function isColegio(): bool
    {
        return $this->role->isColegio();
    }

    /**
     * Check if user is a profesor
     */
    public function isProfesor(): bool
    {
        return $this->role->isProfesor();
    }

    /**
     * Check if user is a padre
     */
    public function isPadre(): bool
    {
        return $this->role->isPadre();
    }

    /**
     * Check if user is a estudiante
     */
    public function isEstudiante(): bool
    {
        return $this->role->isEstudiante();
    }

    /**
     * Check if user is a suplidor
     */
    public function isSuplidor(): bool
    {
        return $this->role->isSuplidor();
    }

    /**
     * Get the students assigned to this user (if padre).
     */
    public function students(): HasMany
    {
        return $this->hasMany(User::class, 'parent_id')->whereHas('role', function($query) {
            $query->where('name', 'estudiante');
        });
    }

    /**
     * Get the parent of this user (if estudiante).
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(User::class, 'parent_id');
    }

    /**
     * Check if user is a parent.
     */
    public function isParent(): bool
    {
        return $this->hasRole('padre');
    }

    /**
     * Check if user is a student.
     */
    public function isStudent(): bool
    {
        return $this->hasRole('estudiante');
    }

    /**
     * Get the students count for a parent.
     */
    public function getStudentsCountAttribute(): int
    {
        return $this->students()->count();
    }

    /**
     * Get the user's full name.
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name && $this->last_name) {
            return $this->first_name . ' ' . $this->last_name;
        }
        return $this->name;
    }

    /**
     * Get the user's display name (full name or username).
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->full_name ?: $this->name;
    }

    /**
     * Get the grade of this user (if estudiante).
     */
    public function grade(): BelongsTo
    {
        return $this->belongsTo(Grade::class);
    }

    /**
     * Get the schedules for this user's grade (if estudiante).
     */
    public function schedules(): HasMany
    {
        if ($this->isStudent() && $this->grade_id) {
            return $this->grade->schedules();
        }
        return $this->newQuery()->whereRaw('1 = 0'); // Empty collection
    }

    /**
     * Get the schedules where this user is the teacher.
     */
    public function teachingSchedules(): HasMany
    {
        if ($this->isProfesor()) {
            return $this->hasMany(Schedule::class, 'teacher_id');
        }
        return $this->newQuery()->whereRaw('1 = 0'); // Empty collection
    }

    /**
     * Get the current class for a student.
     */
    public function getCurrentClassAttribute()
    {
        if (!$this->isStudent() || !$this->grade_id) {
            return null;
        }

        $now = now();
        $currentDay = strtolower($now->format('l'));
        $currentTime = $now->format('H:i:s');

        return $this->schedules()
            ->where('day', $currentDay)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->with(['course', 'teacher'])
            ->first();
    }

    /**
     * Get the next class for a student.
     */
    public function getNextClassAttribute()
    {
        if (!$this->isStudent() || !$this->grade_id) {
            return $this->schedules()
                ->where('day', '>=', strtolower(now()->format('l')))
                ->where('start_time', '>', now()->format('H:i:s'))
                ->with(['course', 'teacher'])
                ->orderBy('day')
                ->orderBy('start_time')
                ->first();
        }

        return null;
    }

    /**
     * Relación con las asistencias como estudiante
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'student_id');
    }

    /**
     * Relación con las asistencias como profesor
     */
    public function teacherAttendances()
    {
        return $this->hasMany(Attendance::class, 'teacher_id');
    }

    /**
     * Obtener asistencias de un estudiante para una fecha específica
     */
    public function attendancesForDate($date)
    {
        return $this->attendances()->where('attendance_date', $date);
    }

    /**
     * Obtener asistencias de un estudiante para un horario específico
     */
    public function attendancesForSchedule($scheduleId)
    {
        return $this->attendances()->where('schedule_id', $scheduleId);
    }
}
