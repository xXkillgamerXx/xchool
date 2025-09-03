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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // create, update, delete
            $table->string('model_type'); // User, Student, Invitation
            $table->unsignedBigInteger('model_id'); // ID del modelo
            $table->unsignedBigInteger('user_id'); // Usuario que realizó la acción
            $table->json('old_values')->nullable(); // Valores anteriores (para updates)
            $table->json('new_values')->nullable(); // Valores nuevos
            $table->text('description'); // Descripción de la acción
            $table->string('ip_address')->nullable(); // IP del usuario
            $table->string('user_agent')->nullable(); // User agent del navegador
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
            $table->index(['user_id']);
            $table->index(['action']);
            $table->index(['created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
