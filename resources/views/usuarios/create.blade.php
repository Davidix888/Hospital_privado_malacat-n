<x-layouts.panel title="Crear usuario">
    <section style="padding-top: 2rem;">
        @if (session('error') || $errors->any())
            <div
                id="user-create-error-popup"
                class="fixed right-6 top-24 z-50 max-w-sm border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-xl"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold">No se pudo crear el usuario</p>
                        <p>{{ session('error', 'Revisa los datos del formulario y corrige los campos marcados.') }}</p>
                    </div>
                    <button
                        type="button"
                        class="text-lg font-semibold leading-none text-red-700/70 transition hover:text-red-700"
                        onclick="document.getElementById('user-create-error-popup')?.remove()"
                    >
                        ×
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('user-create-error-popup')?.remove(), 6000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="space-y-3">
                <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Crear usuario</p>
                <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Registro de usuario</h1>
                <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                    Registro a empleado y creación de su cuenta de usuario.
                </p>
            </div>

            @if ($errors->any())
                <div class="mt-6 border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    Revisa los datos del formulario antes de guardar.
                </div>
            @endif

            <form method="POST" action="{{ route('usuarios.store') }}" class="mt-8 grid gap-8">
                @csrf

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="border border-[#0b1b57]/10 p-5">
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">1. Datos del empleado</h2>
                        <p class="mt-2 text-sm leading-6 text-[#0b1b57]/70">
                            Llena los datos del empleado que se registrara junto con el usuario.
                        </p>

                        <div class="mt-5 grid gap-4">
                            <div>
                                <label for="nombres" class="text-sm font-medium text-[#0b1b57]">Nombres</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa el nombre del empleado.</p>
                                <input
                                    id="nombres"
                                    name="nombres"
                                    type="text"
                                    value="{{ old('nombres') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                                @error('nombres')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="apellidos" class="text-sm font-medium text-[#0b1b57]">Apellidos</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Ingrese el apellido del empleado.</p>
                                <input
                                    id="apellidos"
                                    name="apellidos"
                                    type="text"
                                    value="{{ old('apellidos') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                                @error('apellidos')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="dpi" class="text-sm font-medium text-[#0b1b57]">DPI</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Ingrese el numero de DPI del emppleado.</p>
                                <input
                                    id="dpi"
                                    name="dpi"
                                    type="text"
                                    value="{{ old('dpi') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                    maxlength="13"
                                    inputmode="numeric"
                                    pattern="[0-9]{13}"
                                    placeholder="Ej: 1234567890123"
                                >
                                @error('dpi')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="direccion" class="text-sm font-medium text-[#0b1b57]">Direccion</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Ingrese la dirección del empleado.</p>
                                <input
                                    id="direccion"
                                    name="direccion"
                                    type="text"
                                    value="{{ old('direccion') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                                @error('direccion')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="border border-[#0b1b57]/10 p-5">
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">2. Cargo</h2>
                        <p class="mt-2 text-sm leading-6 text-[#0b1b57]/70">
                            Puedes seleccione el cargo del empleado o cree el nuevo cargo.
                        </p>

                        <div class="mt-5 grid gap-4">
                            <div>
                                <label for="id_cargo" class="text-sm font-medium text-[#0b1b57]">Cargo existente</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Seleccione un cargo.</p>
                                <select
                                    id="id_cargo"
                                    name="id_cargo"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                                    <option value="">Seleccione un cargo</option>
                                    @foreach ($cargos as $cargo)
                                        <option value="{{ $cargo->id_cargo }}" @selected(old('id_cargo') == $cargo->id_cargo)>
                                            {{ $cargo->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_cargo')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cargo_nombre" class="text-sm font-medium text-[#0b1b57]">Nuevo cargo</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Ingrese el nombre del nuevo cargo.</p>
                                <input
                                    id="cargo_nombre"
                                    name="cargo_nombre"
                                    type="text"
                                    value="{{ old('cargo_nombre') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                    placeholder="Ejemplo: Tecnico de laboratorio"
                                >
                                @error('cargo_nombre')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="cargo_descripcion" class="text-sm font-medium text-[#0b1b57]">Descripcion </label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Este campo es opcional y ayuda a describir mejor el cargo.</p>
                                <input
                                    id="cargo_descripcion"
                                    name="cargo_descripcion"
                                    type="text"
                                    value="{{ old('cargo_descripcion') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <div class="border border-[#0b1b57]/10 p-5">
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">3. Datos del usuario</h2>
                        <div class="mt-5 grid gap-4">
                            <div>
                                <label for="id_rol" class="text-sm font-medium text-[#0b1b57]">Rol</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Selecciona el rol, permisos para ver módulos.</p>
                                <select
                                    id="id_rol"
                                    name="id_rol"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                                    <option value="">Selecciona un rol</option>
                                    @foreach ($roles as $role)
                                        <option value="{{ $role->id_rol }}" @selected(old('id_rol') == $role->id_rol)>
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
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Usa un nombre unico, sin repetir. Ej: ldixquiac.</p>
                                <input
                                    id="username"
                                    name="username"
                                    type="text"
                                    value="{{ old('username') }}"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                    placeholder="Ejemplo: ldixquiac"
                                >
                                @error('username')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password" class="text-sm font-medium text-[#0b1b57]">Contraseña</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Debe tener al menos 8 caracteres.</p>
                                <input
                                    id="password"
                                    name="password"
                                    type="password"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                                @error('password')
                                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="text-sm font-medium text-[#0b1b57]">Confirmar contraseña</label>
                                <p class="mt-1 text-xs text-[#0b1b57]/60">Escribe nuevamente la contraseña para confirmar el registro.</p>
                                <input
                                    id="password_confirmation"
                                    name="password_confirmation"
                                    type="password"
                                    class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                >
                            </div>
                        </div>
                    </div>

                    <div class="border border-[#0b1b57]/10 p-5">
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">4. Activación</h2>
                        <p class="mt-2 text-sm leading-6 text-[#0b1b57]/70">
                            Indicar si se desea habilitar el usuario al crearlo.
                        </p>

                        <div class="mt-5">
                            <label for="estado" class="text-sm font-medium text-[#0b1b57]">Estado del usuario</label>
                            <p class="mt-1 text-xs text-[#0b1b57]/60">Elige si el usuario podra ingresar al sistema desde el momento en que se cree.</p>
                            <select
                                id="estado"
                                name="estado"
                                class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                            >
                                <option value="1" @selected(old('estado', '1') === '1')>Activo</option>
                                <option value="0" @selected(old('estado') === '0')>Inactivo</option>
                            </select>
                            @error('estado')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Guardar usuario
                    </button>
                    <a
                        href="{{ route('usuarios.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver al menu
                    </a>
                </div>
            </form>
        </div>
    </section>
</x-layouts.panel>
