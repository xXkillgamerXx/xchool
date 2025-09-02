<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
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
}
