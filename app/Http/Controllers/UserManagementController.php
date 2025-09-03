<?php

namespace App\Http\Controllers;

use App\Mail\InvitationMail;
use App\Mail\CredentialsMail;
use App\Models\Invitation;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class UserManagementController extends Controller
{
    /**
     * Display the user management page (only for colegio role).
     */
    public function index(Request $request): Response
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado. Solo los colegios pueden gestionar usuarios.');
        }

        // Obtener filtros de la request
        $search = $request->get('search', '');
        $role = $request->get('role', '');
        $sort = $request->get('sort', 'name');
        $direction = $request->get('direction', 'asc');

        // Construir query para usuarios con filtros y paginación
        $usersQuery = User::with('role');

        // Aplicar filtro de búsqueda
        if ($search) {
            $usersQuery->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('first_name', 'like', "%{$search}%")
                      ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        // Aplicar filtro por rol
        if ($role) {
            $usersQuery->where('role_id', $role);
        }

        // Aplicar ordenamiento
        $allowedSorts = ['name', 'email', 'role_id', 'created_at'];
        $allowedDirections = ['asc', 'desc'];
        
        if (in_array($sort, $allowedSorts) && in_array($direction, $allowedDirections)) {
            $usersQuery->orderBy($sort, $direction);
        } else {
            $usersQuery->orderBy('name', 'asc');
        }

        // Paginar resultados
        $users = $usersQuery->paginate(15)->withQueryString();

        $invitations = Invitation::with('role')->where('status', 'pending')->get();
        $roles = Role::whereIn('name', ['profesor', 'padre', 'estudiante'])->get();

        return Inertia::render('UserManagement/Index', [
            'users' => $users,
            'invitations' => $invitations,
            'roles' => $roles,
            'filters' => [
                'search' => $search,
                'role' => $role,
                'sort' => $sort,
                'direction' => $direction,
            ],
        ]);
    }

    /**
     * Send invitation to create a new user.
     */
    public function sendInvitation(Request $request)
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $request->validate([
            'email' => 'required|email|unique:users,email|unique:invitations,email',
            'role_id' => 'required|exists:roles,id',
        ]);

        // Verificar que el rol sea válido para invitaciones
        $role = Role::find($request->role_id);
        if (!in_array($role->name, ['profesor', 'padre', 'estudiante'])) {
            return redirect()->back()->withErrors(['role_id' => 'Rol no válido para invitaciones.']);
        }

        // Obtener el rol para generar el nombre de usuario
        $role = Role::find($request->role_id);
        
        // Generar nombre de usuario automáticamente
        $usernameData = $this->generateUsername($role->name);

        // Crear la invitación
        $invitation = Invitation::create([
            'email' => $request->email,
            'name' => $usernameData['username'], // Usar el username generado
            'role_id' => $request->role_id,
            'token' => Invitation::generateToken(),
            'expires_at' => now()->addDays(7), // Expira en 7 días
            'invited_by' => $request->user()->id,
            'status' => 'pending',
        ]);

        // Enviar email de invitación
        $this->sendInvitationEmail($invitation);

        return redirect()->back()->with('success', 'Invitación enviada correctamente.');
    }

    /**
     * Accept invitation and create user account.
     */
    public function acceptInvitation(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)
            ->where('status', 'pending')
            ->where('expires_at', '>', now())
            ->firstOrFail();

        // Generar contraseña temporal
        $password = Str::random(8);

        // Crear el usuario con el username generado
        $user = User::create([
            'name' => $invitation->name, // Usar el username generado
            'email' => $invitation->email,
            'password' => Hash::make($password),
            'role_id' => $invitation->role_id,
            'email_verified_at' => now(),
        ]);

        // Marcar invitación como aceptada
        $invitation->markAsAccepted();

        // Enviar email con credenciales
        $this->sendCredentialsEmail($user, $password);

        return redirect()->route('login')->with('success', 'Cuenta creada exitosamente. Revisa tu email para las credenciales.');
    }

    /**
     * Generate username based on role.
     */
    private function generateUsername(string $roleName): array
    {
        $prefix = match($roleName) {
            'profesor' => 'profesor',
            'padre' => 'padre',
            'estudiante' => 'estudiante',
            'suplidor' => 'suplidor',
            default => 'usuario'
        };

        // Buscar el siguiente número disponible
        $counter = 1;
        do {
            $username = $prefix . str_pad($counter, 2, '0', STR_PAD_LEFT);
            $exists = User::where('name', $username)->exists();
            $counter++;
        } while ($exists);

        return [
            'username' => $username,
            'first_name' => null, // No generar nombre por defecto
            'last_name' => null   // No generar apellido por defecto
        ];
    }

    /**
     * Send invitation email.
     */
    private function sendInvitationEmail(Invitation $invitation)
    {
        try {
            Mail::to($invitation->email)->send(new InvitationMail($invitation));
            
            \Log::info('Invitation email sent successfully', [
                'email' => $invitation->email,
                'token' => $invitation->token,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send invitation email', [
                'email' => $invitation->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Send credentials email.
     */
    private function sendCredentialsEmail(User $user, string $password)
    {
        try {
            Mail::to($user->email)->send(new CredentialsMail($user, $password));
            
            \Log::info('Credentials email sent successfully', [
                'email' => $user->email,
                'user_id' => $user->id,
            ]);
        } catch (\Exception $e) {
            \Log::error('Failed to send credentials email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Delete invitation.
     */
    public function deleteInvitation(Invitation $invitation)
    {
        // Verificar que el usuario sea colegio
        if (!auth()->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        $invitation->delete();

        return redirect()->back()->with('success', 'Invitación eliminada correctamente.');
    }

    /**
     * Send credentials to existing user.
     */
    public function sendCredentials(Request $request, User $user)
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        // Generar una nueva contraseña temporal
        $temporaryPassword = Str::random(12);
        
        // Actualizar la contraseña del usuario
        $user->update([
            'password' => Hash::make($temporaryPassword)
        ]);

        // Enviar las credenciales por correo
        $this->sendCredentialsEmail($user, $temporaryPassword);

        return redirect()->back()->with('success', "Credenciales enviadas exitosamente a {$user->email}");
    }

    /**
     * Delete a user.
     */
    public function deleteUser(Request $request, User $user)
    {
        // Verificar que el usuario sea colegio
        if (!$request->user()->isColegio()) {
            abort(403, 'Acceso denegado.');
        }

        // No permitir eliminar usuarios colegio
        if ($user->role->name === 'colegio') {
            abort(403, 'No se puede eliminar un usuario colegio.');
        }

        // No permitir eliminar al usuario actual
        if ($user->id === $request->user()->id) {
            abort(403, 'No se puede eliminar tu propia cuenta.');
        }

        $user->delete();

        return redirect()->back()->with('success', 'Usuario eliminado exitosamente.');
    }
}
