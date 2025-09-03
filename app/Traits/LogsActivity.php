<?php

namespace App\Traits;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

trait LogsActivity
{
    /**
     * Boot the trait.
     */
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            $model->logActivity('create');
        });

        static::updated(function ($model) {
            $model->logActivity('update');
        });

        static::deleted(function ($model) {
            $model->logActivity('delete');
        });
    }

    /**
     * Log an activity.
     */
    public function logActivity(string $action, array $additionalData = []): void
    {
        if (!Auth::check()) {
            return;
        }

        $description = $this->getActivityDescription($action);
        
        ActivityLog::create([
            'action' => $action,
            'model_type' => get_class($this),
            'model_id' => $this->id,
            'user_id' => Auth::id(),
            'old_values' => $action === 'update' ? $this->getOriginal() : null,
            'new_values' => $action === 'update' ? $this->getAttributes() : null,
            'description' => $description,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    /**
     * Get the activity description.
     */
    protected function getActivityDescription(string $action): string
    {
        $modelName = $this->getModelDisplayName();
        
        switch ($action) {
            case 'create':
                return "Creó {$modelName}";
            case 'update':
                return "Actualizó {$modelName}";
            case 'delete':
                return "Eliminó {$modelName}";
            default:
                return "Realizó acción '{$action}' en {$modelName}";
        }
    }

    /**
     * Get the display name for the model.
     */
    protected function getModelDisplayName(): string
    {
        if (method_exists($this, 'getDisplayNameAttribute')) {
            return $this->display_name;
        }

        if (isset($this->name)) {
            return $this->name;
        }

        if (isset($this->first_name) && isset($this->last_name)) {
            return "{$this->first_name} {$this->last_name}";
        }

        return class_basename($this);
    }
}
