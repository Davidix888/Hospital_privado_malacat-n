<x-layouts.panel title="Gestionar estado de usuarios">
    <section style="padding-top: 2rem;">
        <div class="mb-6 flex justify-end">
            <a
                href="{{ route('usuarios.index') }}"
                class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
            >
                Volver al men&uacute;
            </a>
        </div>

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Activar y desactivar</p>
                <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Control de accesos</h1>
                <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                    Gestiona el estado de los usuarios sin eliminar su informaci&oacute;n ni su historial dentro del sistema.
                </p>
            </div>

            @if (session('status'))
                <div class="mt-6 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mt-6 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ $errors->first() }}
                </div>
            @endif

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
                            <th class="w-[20%] px-4 py-3 text-center font-semibold align-middle">Acci&oacute;n</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 text-center align-middle font-medium break-words">{{ $user->username }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $user->employee_name }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $user->role_name }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <span class="inline-flex items-center justify-center {{ $user->estado ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $user->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center align-middle">
                                    @if ($user->estado && ! auth()->user()->is($user))
                                        <form method="POST" action="{{ route('usuarios.deactivate', $user) }}" style="display: flex; justify-content: center;">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                style="min-width: 118px; border-radius: 1rem; background: #dc2626; color: #ffffff; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 600;"
                                            >
                                                Desactivar
                                            </button>
                                        </form>
                                    @elseif ($user->estado)
                                        <div style="display: flex; justify-content: center;">
                                            <button
                                                type="button"
                                                disabled
                                                style="min-width: 118px; border-radius: 1rem; background: #cbd5e1; color: #334155; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 600; cursor: not-allowed;"
                                            >
                                                Usuario actual
                                            </button>
                                        </div>
                                    @else
                                        <form method="POST" action="{{ route('usuarios.reactivate', $user) }}" style="display: flex; justify-content: center;">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                style="min-width: 118px; border-radius: 1rem; background: #059669; color: #ffffff; padding: 0.5rem 1rem; font-size: 0.75rem; font-weight: 600;"
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
