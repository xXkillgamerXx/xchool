<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ActivityLogController extends Controller
{
    /**
     * Display the activity log page.
     */
    public function index(Request $request): Response
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado. Solo los colegios pueden ver el historial de actividades.');
        }

        $query = ActivityLog::with(['user:id,name,first_name,last_name'])
            ->orderBy('created_at', 'desc');

        // Filtros
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        if ($request->filled('model_type')) {
            $query->where('model_type', $request->model_type);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $activities = $query->paginate(50);

        // Obtener opciones para filtros
        $actions = ActivityLog::distinct()->pluck('action')->sort();
        $modelTypes = ActivityLog::distinct()->pluck('model_type')->sort();
        $users = User::where('role_id', '!=', 1)->get(['id', 'name', 'first_name', 'last_name']);

        return Inertia::render('ActivityLog/Index', [
            'activities' => $activities,
            'filters' => [
                'action' => $request->action,
                'model_type' => $request->model_type,
                'user_id' => $request->user_id,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
            'options' => [
                'actions' => $actions,
                'model_types' => $modelTypes,
                'users' => $users,
            ],
        ]);
    }

    /**
     * Get activity logs for a specific model.
     */
    public function forModel(Request $request, string $modelType, int $modelId): Response
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $activities = ActivityLog::with(['user:id,name,first_name,last_name'])
            ->where('model_type', $modelType)
            ->where('model_id', $modelId)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('ActivityLog/ModelHistory', [
            'activities' => $activities,
            'modelType' => $modelType,
            'modelId' => $modelId,
        ]);
    }
}
