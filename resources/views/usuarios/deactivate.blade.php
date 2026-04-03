<x-layouts.panel title="Desactivar usuarios">
    <section style="padding-top: 2rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Desactivar usuario</p>
                <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Control de accesos</h1>
                <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                    Desactiva usuarios activos sin eliminar su informacion ni su historial dentro del sistema.
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

            <form method="GET" action="{{ route('usuarios.deactivate.index') }}" class="mt-8 grid gap-4 lg:grid-cols-3">
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
                <table class="w-full table-fixed border border-[#0b1b57]/10 text-left text-sm text-[#0b1b57]">
                    <thead class="bg-[#0b1b57] text-white">
                        <tr>
                            <th class="w-[22%] px-4 py-3 font-semibold">Usuario</th>
                            <th class="w-[33%] px-4 py-3 font-semibold">Empleado</th>
                            <th class="w-[25%] px-4 py-3 font-semibold">Rol</th>
                            <th class="w-[20%] px-4 py-3 font-semibold">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 font-medium break-words">{{ $user->username }}</td>
                                <td class="px-4 py-3 break-words">{{ $user->employee_name }}</td>
                                <td class="px-4 py-3 break-words">{{ $user->role_name }}</td>
                                <td class="px-4 py-3">
                                    <form method="POST" action="{{ route('usuarios.deactivate', $user) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button
                                            type="submit"
                                            class="rounded-[1rem] bg-red-600 px-4 py-2 text-xs font-semibold text-white transition hover:bg-red-700"
                                        >
                                            Desactivar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-center text-[#0b1b57]/70">
                                    No hay usuarios activos disponibles para desactivar.
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
