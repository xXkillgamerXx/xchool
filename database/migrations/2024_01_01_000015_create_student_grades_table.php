<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('student_grades', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('course_id')->constrained('courses')->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->string('assignment_name'); // Nombre de la tarea/examen
            $table->enum('type', ['exam', 'homework', 'project', 'quiz', 'participation', 'other'])->default('exam');
            $table->decimal('score', 5, 2); // Puntuación obtenida
            $table->decimal('max_score', 5, 2)->default(100.00); // Puntuación máxima
            $table->text('comments')->nullable(); // Comentarios del profesor
            $table->date('grade_date'); // Fecha de la calificación
            $table->timestamps();

            // Índices para optimizar consultas
            $table->index(['student_id', 'grade_date']);
            $table->index(['course_id', 'grade_date']);
            $table->index(['teacher_id', 'grade_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('student_grades');
    }
};
