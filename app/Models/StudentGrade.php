<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentGrade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'course_id',
        'teacher_id',
        'assignment_name',
        'type',
        'score',
        'max_score',
        'comments',
        'grade_date',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'max_score' => 'decimal:2',
        'grade_date' => 'date',
    ];

    /**
     * Relación con el estudiante
     */
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    /**
     * Relación con el curso
     */
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Relación con el profesor
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Scope para filtrar por estudiante
     */
    public function scopeForStudent($query, $studentId)
    {
        return $query->where('student_id', $studentId);
    }

    /**
     * Scope para filtrar por profesor
     */
    public function scopeForTeacher($query, $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }

    /**
     * Scope para filtrar por curso
     */
    public function scopeForCourse($query, $courseId)
    {
        return $query->where('course_id', $courseId);
    }

    /**
     * Scope para filtrar por fecha
     */
    public function scopeForDate($query, $date)
    {
        return $query->where('grade_date', $date);
    }

    /**
     * Obtener el porcentaje de la calificación
     */
    public function getPercentageAttribute(): float
    {
        if ($this->max_score > 0) {
            return round(($this->score / $this->max_score) * 100, 2);
        }
        return 0;
    }

    /**
     * Obtener la letra de calificación (A, B, C, D, F)
     */
    public function getLetterGradeAttribute(): string
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'A';
        if ($percentage >= 80) return 'B';
        if ($percentage >= 70) return 'C';
        if ($percentage >= 60) return 'D';
        return 'F';
    }

    /**
     * Obtener el color de la calificación
     */
    public function getGradeColorAttribute(): string
    {
        $percentage = $this->percentage;
        
        if ($percentage >= 90) return 'green';
        if ($percentage >= 80) return 'blue';
        if ($percentage >= 70) return 'yellow';
        if ($percentage >= 60) return 'orange';
        return 'red';
    }

    /**
     * Verificar si la calificación es aprobatoria
     */
    public function getIsPassingAttribute(): bool
    {
        return $this->percentage >= 60;
    }

    /**
     * Obtener el tipo de calificación en español
     */
    public function getTypeInSpanishAttribute(): string
    {
        return match($this->type) {
            'exam' => 'Examen',
            'homework' => 'Tarea',
            'project' => 'Proyecto',
            'quiz' => 'Quiz',
            'participation' => 'Participación',
            'other' => 'Otro',
            default => 'Desconocido'
        };
    }
}
