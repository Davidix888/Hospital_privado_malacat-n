<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreManagedUserRequest;
use App\Http\Requests\UpdateManagedUserRequest;
use App\Models\Cargo;
use App\Models\Employee;
use App\Models\Role;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Throwable;

class UserManagementController extends Controller
{
    public function index(): View
    {
        $roles = $this->roles();

        return view('usuarios.index', [
            'stats' => [
                'total' => User::count(),
                'active' => User::where('estado', true)->count(),
                'roles' => $roles->count(),
                'availableEmployees' => Employee::where('estado', true)->whereDoesntHave('user')->count(),
            ],
        ]);
    }

    public function create(): View
    {
        return view('usuarios.create', [
            'roles' => $this->roles(),
            'cargos' => $this->cargos(),
        ]);
    }

    public function store(StoreManagedUserRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        try {
            DB::transaction(function () use ($validated, $request): void {
                $cargoId = $validated['id_cargo'] ?? null;

                if (! $cargoId) {
                    $cargo = Cargo::firstOrCreate(
                        ['nombre' => trim($validated['cargo_nombre'])],
                        ['descripcion' => $validated['cargo_descripcion'] ?? null],
                    );

                    $cargoId = $cargo->id_cargo;
                }

                $employee = Employee::create([
                    'id_cargo' => (int) $cargoId,
                    'nombres' => trim($validated['nombres']),
                    'apellidos' => trim($validated['apellidos']),
                    'dpi' => trim($validated['dpi']),
                    'direccion' => trim($validated['direccion']),
                    'estado' => true,
                ]);

                User::create([
                    'id_empleado' => (int) $employee->id_empleado,
                    'id_rol' => (int) $validated['id_rol'],
                    'username' => $validated['username'],
                    'password' => $validated['password'],
                    'estado' => $request->boolean('estado'),
                    'fecha_creacion' => now(),
                ]);
            });
        } catch (Throwable $exception) {
            report($exception);

            return back()
                ->withInput()
                ->with('error', 'No se pudo crear el usuario. Verifica los datos e intenta nuevamente.');
        }

        return redirect()
            ->route('usuarios.list')
            ->with('status', 'Usuario creado correctamente.');
    }

    public function list(Request $request): View
    {
        $users = $this->usersQuery($request)
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.list', [
            'users' => $users,
            'filters' => $request->only(['q', 'estado', 'rol']),
            'roles' => $this->roles(),
        ]);
    }

    public function edit(User $user): View
    {
        $user->load(['rol', 'empleado']);

        return view('usuarios.edit', [
            'managedUser' => $user,
            'roles' => $this->roles(),
        ]);
    }

    public function update(UpdateManagedUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        $payload = [
            'id_rol' => (int) $validated['id_rol'],
            'username' => $validated['username'],
            'estado' => $request->boolean('estado'),
        ];

        if (! empty($validated['password'])) {
            $payload['password'] = $validated['password'];
        }

        $user->update($payload);

        return redirect()
            ->route('usuarios.list')
            ->with('status', 'Usuario actualizado correctamente.');
    }

    public function deactivateIndex(Request $request): View
    {
        $users = $this->usersQuery($request)
            ->paginate(10)
            ->withQueryString();

        return view('usuarios.deactivate', [
            'users' => $users,
            'filters' => $request->only(['q', 'rol', 'estado']),
            'roles' => $this->roles(),
        ]);
    }

    public function deactivate(User $user, Request $request): RedirectResponse
    {
        if ($request->user()->is($user)) {
            return back()->withErrors([
                'user' => 'No puedes desactivar tu propio usuario mientras estas autenticado.',
            ]);
        }

        $user->update([
            'estado' => false,
        ]);

        return redirect()
            ->route('usuarios.deactivate.index')
            ->with('status', 'Usuario desactivado correctamente.');
    }

    public function reactivate(User $user): RedirectResponse
    {
        $user->update([
            'estado' => true,
        ]);

        return redirect()
            ->route('usuarios.deactivate.index')
            ->with('status', 'Usuario activado correctamente.');
    }

    private function usersQuery(Request $request)
    {
        return User::query()
            ->with(['rol', 'empleado'])
            ->when($request->filled('q'), function ($query) use ($request): void {
                $term = trim((string) $request->string('q'));

                $query->where(function ($innerQuery) use ($term): void {
                    $innerQuery->where('username', 'ILIKE', "%{$term}%")
                        ->orWhereHas('rol', fn ($roleQuery) => $roleQuery->where('nombre', 'ILIKE', "%{$term}%"))
                        ->orWhereHas('empleado', function ($employeeQuery) use ($term): void {
                            $employeeQuery->where('nombres', 'ILIKE', "%{$term}%")
                                ->orWhere('apellidos', 'ILIKE', "%{$term}%")
                                ->orWhereRaw("TRIM(nombres || ' ' || apellidos) ILIKE ?", ["%{$term}%"]);
                        });
                });
            })
            ->when($request->filled('estado'), function ($query) use ($request): void {
                $query->where('estado', $request->string('estado')->value() === 'activo');
            })
            ->when($request->filled('rol'), function ($query) use ($request): void {
                $query->where('id_rol', (int) $request->string('rol')->value());
            })
            ->orderByDesc('fecha_creacion');
    }

    private function roles()
    {
        $this->syncBaseRoles();

        return Role::query()
            ->orderBy('nombre')
            ->get();
    }

    private function cargos()
    {
        return Cargo::query()
            ->orderBy('nombre')
            ->get();
    }

    private function syncBaseRoles(): void
    {
        foreach (array_keys(config('access.roles', [])) as $roleKey) {
            Role::query()->firstOrCreate(
                ['nombre' => Str::headline($roleKey)],
                ['descripcion' => 'Rol base sincronizado desde la configuracion de acceso.'],
            );
        }
    }
}
