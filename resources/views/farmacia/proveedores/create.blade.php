<x-layouts.panel title="Registrar proveedor">
    <section style="padding-top: 2rem;">
        @if (session('error') || $errors->any())
            <div
                id="supplier-create-error-popup"
                class="fixed right-6 top-24 z-50 max-w-sm border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-xl"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold">No se pudo registrar el proveedor</p>
                        <p>{{ session('error', 'Revisa los datos del formulario y corrige los campos marcados.') }}</p>
                    </div>
                    <button
                        type="button"
                        class="text-lg font-semibold leading-none text-red-700/70 transition hover:text-red-700"
                        onclick="document.getElementById('supplier-create-error-popup')?.remove()"
                    >
                        ×
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('supplier-create-error-popup')?.remove(), 6000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Registrar proveedor</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Ingresa los datos del proveedor para dejarlo disponible en las compras del area de farmacia.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver a farmacia
                    </a>
                </div>
            </div>

            <form method="POST" action="{{ route('farmacia.suppliers.store') }}" class="mt-8 grid gap-6 lg:grid-cols-2">
                @csrf

                <div>
                    <label for="nombre" class="text-sm font-medium text-[#0b1b57]">Nombre del proveedor</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa el nombre comercial o legal del proveedor.</p>
                    <input
                        id="nombre"
                        name="nombre"
                        type="text"
                        value="{{ old('nombre') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('nombre')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="correo" class="text-sm font-medium text-[#0b1b57]">Correo del proveedor</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Este campo es opcional, pero ayuda a futuras comunicaciones.</p>
                    <input
                        id="correo"
                        name="correo"
                        type="email"
                        value="{{ old('correo') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="ejemplo@proveedor.com"
                    >
                    @error('correo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="telefono" class="text-sm font-medium text-[#0b1b57]">Telefono del proveedor</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa el numero principal de contacto del proveedor. El sistema lo guardara como telefono principal.</p>
                    <input
                        id="telefono"
                        name="telefono"
                        type="text"
                        value="{{ old('telefono') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="Ejemplo: 55554444"
                    >
                    @error('telefono')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="direccion" class="text-sm font-medium text-[#0b1b57]">Direccion</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Escribe la direccion principal del proveedor.</p>
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

                <div>
                    <label for="estado" class="text-sm font-medium text-[#0b1b57]">Estado del proveedor</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Define si el proveedor quedara disponible para compras.</p>
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

                <div class="flex gap-3 lg:col-span-2">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Guardar proveedor
                    </button>
                    <a
                        href="{{ route('farmacia.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </section>
</x-layouts.panel>
