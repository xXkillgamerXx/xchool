<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];

    /**
     * Get the users for this role.
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role is colegio
     */
    public function isColegio(): bool
    {
        return $this->name === 'colegio';
    }

    /**
     * Check if role is profesor
     */
    public function isProfesor(): bool
    {
        return $this->name === 'profesor';
    }

    /**
     * Check if role is padre
     */
    public function isPadre(): bool
    {
        return $this->name === 'padre';
    }

    /**
     * Check if role is estudiante
     */
    public function isEstudiante(): bool
    {
        return $this->name === 'estudiante';
    }

    /**
     * Check if role is suplidor
     */
    public function isSuplidor(): bool
    {
        return $this->name === 'suplidor';
    }
}
