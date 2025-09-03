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
}
