<x-layouts.panel title="Editar usuario">
    <section style="padding-top: 2rem;">

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Editar usuario</p>
                <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Actualizaci&oacute;n de datos</h1>
                <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                    Modifica la informaci&oacute;n del usuario seleccionado y cambia la contrase&ntilde;a solo cuando sea necesario.
                </p>
            </div>

            @if (session('error') || $errors->any())
                <div class="mt-6 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error', 'Revisa los datos del formulario antes de guardar.') }}
                </div>
            @endif

            <form method="POST" action="{{ route('usuarios.update', $managedUser) }}" class="mt-8 grid gap-6 lg:grid-cols-2">
                @csrf
                @method('PUT')

                <div>
                    <label class="text-sm font-medium text-[#0b1b57]">Empleado vinculado</label>
                    <input
                        type="text"
                        value="{{ $managedUser->employee_name }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-[#f4f7fb] px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        readonly
                    >
                </div>

                <div>
                    <label for="id_rol" class="text-sm font-medium text-[#0b1b57]">Rol</label>
                    <select
                        id="id_rol"
                        name="id_rol"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                        @foreach ($roles as $role)
                            <option value="{{ $role->id_rol }}" @selected(old('id_rol', $managedUser->id_rol) == $role->id_rol)>
                                {{ $role->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_rol')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="username" class="text-sm font-medium text-[#0b1b57]">Nombre de usuario</label>
                    <input
                        id="username"
                        name="username"
                        type="text"
                        value="{{ old('username', $managedUser->username) }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('username')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center gap-3">
                    <input
                        id="estado"
                        name="estado"
                        type="hidden"
                        value="0"
                    >
                    <input
                        id="estado"
                        name="estado"
                        type="checkbox"
                        value="1"
                        class="h-4 w-4"
                        @checked(old('estado', $managedUser->estado))
                    >
                    <label for="estado" class="text-sm text-[#0b1b57]">Usuario activo</label>
                </div>

                <div>
                    <label for="password" class="text-sm font-medium text-[#0b1b57]">Nueva contrase&ntilde;a</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    <p class="mt-2 text-xs text-[#0b1b57]/60">Deja este campo vac&iacute;o si no quieres cambiar la contrase&ntilde;a.</p>
                    @error('password')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="text-sm font-medium text-[#0b1b57]">Confirmar nueva contrase&ntilde;a</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                </div>

                <div class="flex gap-3 lg:col-span-2">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Guardar cambios
                    </button>
                    <a
                        href="{{ route('usuarios.list') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver al listado
                    </a>
                    <a
                        href="{{ route('usuarios.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver al men&uacute;
                    </a>
                </div>
            </form>
        </div>
    </section>
</x-layouts.panel>
