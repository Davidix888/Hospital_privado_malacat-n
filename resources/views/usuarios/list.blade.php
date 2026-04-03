<x-layouts.panel title="Listar usuarios">
    <section style="padding-top: 2rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-end lg:justify-between">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Listar y buscar</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Usuarios registrados</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Busca usuarios por nombre, username o rol y accede rapidamente a la edicion de sus datos.
                    </p>
                </div>

                <a
                    href="{{ route('usuarios.create') }}"
                    class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                >
                    Nuevo usuario
                </a>
            </div>

            @if (session('status'))
                <div class="mt-6 border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-700">
                    {{ session('status') }}
                </div>
            @endif

            <form method="GET" action="{{ route('usuarios.list') }}" class="mt-8 grid gap-4 lg:grid-cols-4">
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
                    <option value="activo" @selected(($filters['estado'] ?? '') === 'activo')>Activo</option>
                    <option value="inactivo" @selected(($filters['estado'] ?? '') === 'inactivo')>Inactivo</option>
                </select>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Buscar
                    </button>
                    <a
                        href="{{ route('usuarios.list') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Limpiar
                    </a>
                </div>
            </form>

            <div class="mt-8 w-full overflow-x-auto">
                <table class="w-full table-fixed border border-[#0b1b57]/10 text-left text-sm text-[#0b1b57]">
                    <thead class="bg-[#0b1b57] text-white">
                        <tr>
                            <th class="w-[16%] px-4 py-3 font-semibold">Usuario</th>
                            <th class="w-[24%] px-4 py-3 font-semibold">Empleado</th>
                            <th class="w-[18%] px-4 py-3 font-semibold">Rol</th>
                            <th class="w-[12%] px-4 py-3 font-semibold">Estado</th>
                            <th class="w-[18%] px-4 py-3 font-semibold">Creado</th>
                            <th class="w-[12%] px-4 py-3 font-semibold">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 font-medium break-words">{{ $user->username }}</td>
                                <td class="px-4 py-3 break-words">{{ $user->employee_name }}</td>
                                <td class="px-4 py-3 break-words">{{ $user->role_name }}</td>
                                <td class="px-4 py-3">
                                    <span class="{{ $user->estado ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $user->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                                <td class="px-4 py-3">{{ optional($user->fecha_creacion)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3">
                                    <a
                                        href="{{ route('usuarios.edit', $user) }}"
                                        class="rounded-[1rem] border border-[#0b1b57]/20 px-4 py-2 text-xs font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                                    >
                                        Editar
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-center text-[#0b1b57]/70">
                                    No se encontraron usuarios con los filtros indicados.
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
