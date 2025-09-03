<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'action',
        'model_type',
        'model_id',
        'user_id',
        'old_values',
        'new_values',
        'description',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed the action.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the model that was affected.
     */
    public function subject()
    {
        return $this->morphTo('model');
    }

    /**
     * Scope a query to only include logs for a specific model.
     */
    public function scopeForModel($query, $modelType, $modelId = null)
    {
        $query->where('model_type', $modelType);
        
        if ($modelId) {
            $query->where('model_id', $modelId);
        }
        
        return $query;
    }

    /**
     * Scope a query to only include logs for a specific action.
     */
    public function scopeForAction($query, $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope a query to only include logs for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Get a human-readable description of the action.
     */
    public function getActionDescriptionAttribute(): string
    {
        $actions = [
            'create' => 'Creó',
            'update' => 'Actualizó',
            'delete' => 'Eliminó',
            'invite' => 'Envió invitación a',
            'accept_invitation' => 'Aceptó invitación de',
        ];

        return $actions[$this->action] ?? $this->action;
    }

    /**
     * Get the formatted date.
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('d/m/Y H:i:s');
    }
}
