<x-layouts.panel title="Registrar medicamento">
    <section style="padding-top: 2rem;">
        @if (session('error') || $errors->any())
            <div
                id="medicine-create-error-popup"
                class="fixed right-6 top-24 z-50 max-w-sm border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-xl"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold">No se pudo registrar el medicamento</p>
                        <p>{{ session('error', 'Revisa los datos del formulario y corrige los campos marcados.') }}</p>
                    </div>
                    <button
                        type="button"
                        class="text-lg font-semibold leading-none text-red-700/70 transition hover:text-red-700"
                        onclick="document.getElementById('medicine-create-error-popup')?.remove()"
                    >
                        &times;
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('medicine-create-error-popup')?.remove(), 6000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Registrar medicamento</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Crea el medicamento con su categoria, presentacion, precio de venta y stock minimo para dejarlo
                        disponible en compras e inventario.
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

            <form method="POST" action="{{ route('farmacia.medicines.store') }}" class="mt-8 grid gap-6 lg:grid-cols-2">
                @csrf

                <div>
                    <label for="nombre" class="text-sm font-medium text-[#0b1b57]">Nombre del medicamento</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Escribe el nombre principal del medicamento.</p>
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
                    <label for="precio_venta" class="text-sm font-medium text-[#0b1b57]">Precio de venta</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa el precio al que se vendera en farmacia.</p>
                    <input
                        id="precio_venta"
                        name="precio_venta"
                        type="number"
                        step="0.01"
                        min="0.01"
                        value="{{ old('precio_venta') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="Ejemplo: 15.50"
                    >
                    @error('precio_venta')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="descripcion" class="text-sm font-medium text-[#0b1b57]">Descripcion del medicamento</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Puedes dejar una descripcion breve del medicamento.</p>
                    <input
                        id="descripcion"
                        name="descripcion"
                        type="text"
                        value="{{ old('descripcion') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('descripcion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="id_categoria" class="text-sm font-medium text-[#0b1b57]">Categoria existente</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Si ya existe una categoria, puedes seleccionarla aqui.</p>
                    <select
                        id="id_categoria"
                        name="id_categoria"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                        <option value="">Selecciona una categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id_categoria }}" @selected(old('id_categoria') == $category->id_categoria)>
                                {{ $category->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_categoria')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nueva_categoria" class="text-sm font-medium text-[#0b1b57]">Nueva categoria</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Si no existe, escribe aqui la nueva categoria.</p>
                    <input
                        id="nueva_categoria"
                        name="nueva_categoria"
                        type="text"
                        value="{{ old('nueva_categoria') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('nueva_categoria')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="descripcion_categoria" class="text-sm font-medium text-[#0b1b57]">Descripcion de categoria</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Opcional. Solo aplica si registras una categoria nueva.</p>
                    <input
                        id="descripcion_categoria"
                        name="descripcion_categoria"
                        type="text"
                        value="{{ old('descripcion_categoria') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                </div>

                <div>
                    <label for="id_presentacion" class="text-sm font-medium text-[#0b1b57]">Presentacion existente</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Si ya existe una presentacion, puedes seleccionarla aqui.</p>
                    <select
                        id="id_presentacion"
                        name="id_presentacion"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                        <option value="">Selecciona una presentacion</option>
                        @foreach ($presentations as $presentation)
                            <option value="{{ $presentation->id_presentacion }}" @selected(old('id_presentacion') == $presentation->id_presentacion)>
                                {{ $presentation->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_presentacion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="nueva_presentacion" class="text-sm font-medium text-[#0b1b57]">Nueva presentacion</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Si no existe, escribe aqui la nueva presentacion.</p>
                    <input
                        id="nueva_presentacion"
                        name="nueva_presentacion"
                        type="text"
                        value="{{ old('nueva_presentacion') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('nueva_presentacion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="descripcion_presentacion" class="text-sm font-medium text-[#0b1b57]">Descripcion de presentacion</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Opcional. Solo aplica si registras una presentacion nueva.</p>
                    <input
                        id="descripcion_presentacion"
                        name="descripcion_presentacion"
                        type="text"
                        value="{{ old('descripcion_presentacion') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                </div>

                <div>
                    <label for="stock_minimo" class="text-sm font-medium text-[#0b1b57]">Stock minimo</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Cantidad minima antes de mostrar la alerta de reabastecimiento.</p>
                    <input
                        id="stock_minimo"
                        name="stock_minimo"
                        type="number"
                        min="1"
                        value="{{ old('stock_minimo') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('stock_minimo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="text-sm font-medium text-[#0b1b57]">Estado del medicamento</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Define si el medicamento quedara disponible para compras.</p>
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
                        Guardar medicamento
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
