<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\LogsActivity;

class Grade extends Model
{
    use HasFactory, LogsActivity;

    protected $fillable = [
        'name',
        'section',
        'description',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    // Relaciones
    public function students()
    {
        return $this->hasMany(User::class)->where('role_id', 4); // role_id 4 = estudiante
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // Accessors
    public function getFullNameAttribute()
    {
        if ($this->section && $this->section !== '') {
            return $this->name . ' - ' . $this->section;
        }
        return $this->name;
    }

    public function getStudentsCountAttribute()
    {
        return $this->students()->count();
    }
}
