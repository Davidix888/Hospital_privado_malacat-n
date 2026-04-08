<x-layouts.panel title="Gestión del estado de usuarios">
    <section style="padding-top: 2rem;">
        @if (session('status'))
            <div
                id="user-status-popup"
                class="fixed right-6 top-24 z-50 max-w-sm border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-xl"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold">Proceso completado</p>
                        <p>{{ session('status') }}</p>
                    </div>
                    <button
                        type="button"
                        class="text-lg font-semibold leading-none text-emerald-700/70 transition hover:text-emerald-700"
                        onclick="document.getElementById('user-status-popup')?.remove()"
                    >
                        &times;
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('user-status-popup')?.remove(), 5000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Usuarios</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Control de accesos</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Gestiona el estado de los usuarios sin eliminar su información ni su historial dentro del sistema.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('usuarios.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver a usuarios
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('usuarios.deactivate.index') }}" class="mt-8 grid gap-4 lg:grid-cols-4">
                <input
                    name="q"
                    type="text"
                    value="{{ $filters['q'] ?? '' }}"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    placeholder="Buscar por nombre o username"
                >

                <select
                    name="rol"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                >
                    <option value="">Todos los roles</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->id_rol }}" @selected(($filters['rol'] ?? '') == $role->id_rol)>
                            {{ $role->nombre }}
                        </option>
                    @endforeach
                </select>

                <select
                    name="estado"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                >
                    <option value="">Todos los estados</option>
                    <option value="activo" @selected(($filters['estado'] ?? '') === 'activo')>Activos</option>
                    <option value="inactivo" @selected(($filters['estado'] ?? '') === 'inactivo')>Inactivos</option>
                </select>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Buscar
                    </button>
                    <a
                        href="{{ route('usuarios.deactivate.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Limpiar
                    </a>
                </div>
            </form>

            <div class="mt-8 w-full overflow-x-auto">
                <table class="w-full table-fixed border border-[#0b1b57]/10 text-sm text-[#0b1b57]">
                    <thead class="bg-[#0b1b57] text-white">
                        <tr>
                            <th class="w-[18%] px-4 py-3 text-center font-semibold align-middle">Usuario</th>
                            <th class="w-[28%] px-4 py-3 text-center font-semibold align-middle">Empleado</th>
                            <th class="w-[20%] px-4 py-3 text-center font-semibold align-middle">Rol</th>
                            <th class="w-[14%] px-4 py-3 text-center font-semibold align-middle">Estado</th>
                            <th class="w-[20%] px-4 py-3 text-center font-semibold align-middle">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 text-center align-middle font-medium break-words">{{ $user->username }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $user->employee_name }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $user->role_name }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <span
                                        class="inline-flex min-w-[108px] items-center justify-center rounded-[999px] px-3 py-1 text-xs font-semibold"
                                        style="{{ $user->estado ? 'background:#dcfce7;color:#166534;' : 'background:#fee2e2;color:#991b1b;' }}"
                                    >
                                        {{ $user->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center align-middle">
                                    @if ($user->estado && ! auth()->user()->is($user))
                                        <form method="POST" action="{{ route('usuarios.deactivate', $user) }}" class="flex justify-center">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="inline-flex min-w-[118px] cursor-pointer items-center justify-center rounded-[1rem] px-4 py-2 text-xs font-semibold"
                                                style="background:#dc2626;color:#ffffff;"
                                            >
                                                Desactivar
                                            </button>
                                        </form>
                                    @elseif ($user->estado)
                                        <div class="flex justify-center">
                                            <button
                                                type="button"
                                                disabled
                                                class="inline-flex min-w-[118px] cursor-not-allowed items-center justify-center rounded-[1rem] px-4 py-2 text-xs font-semibold"
                                                style="background:#cbd5e1;color:#334155;"
                                            >
                                                Usuario actual
                                            </button>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('usuarios.reactivate', $user) }}" class="flex justify-center">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="inline-flex min-w-[118px] cursor-pointer items-center justify-center rounded-[1rem] px-4 py-2 text-xs font-semibold"
                                                style="background:#059669;color:#ffffff;"
                                            >
                                                Activar
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-[#0b1b57]/70">
                                    No hay usuarios disponibles con los filtros indicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </section>
</x-layouts.panel>
