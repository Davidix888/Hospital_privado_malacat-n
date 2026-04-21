<x-layouts.panel title="Registrar medicamento">
    <section style="padding-top: 2rem;">
        @php
            $categoryMode = old('modo_categoria', $categories->isNotEmpty() ? 'existente' : 'nueva');
            $presentationMode = old('modo_presentacion', $presentations->isNotEmpty() ? 'existente' : 'nueva');
        @endphp

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
                        Crea el medicamento con su categor&iacute;a, presentaci&oacute;n, precio de venta y stock m&iacute;nimo para dejarlo
                        disponible en compras e inventario.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.medicines.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver al cat&aacute;logo
                    </a>
                </div>
            </div>

            @include('farmacia.compras.partials.navigation', ['current' => 'medicines.index'])

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
                        placeholder="Ej: Paracetamol"
                    >
                    @error('nombre')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="precio_venta" class="text-sm font-medium text-[#0b1b57]">Precio de venta</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa el precio al que se vender&aacute; en farmacia.</p>
                    <input
                        id="precio_venta"
                        name="precio_venta"
                        type="number"
                        step="0.01"
                        min="0.01"
                        value="{{ old('precio_venta') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="Ej: 15.50"
                    >
                    @error('precio_venta')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2">
                    <label for="descripcion" class="text-sm font-medium text-[#0b1b57]">Descripci&oacute;n del medicamento</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Puedes dejar una descripci&oacute;n breve del medicamento.</p>
                    <input
                        id="descripcion"
                        name="descripcion"
                        type="text"
                        value="{{ old('descripcion') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="Ej: Tabletas recubiertas de 500 mg"
                    >
                    @error('descripcion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2 border border-[#0b1b57]/10 bg-[#f8faff] p-5">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Categor&iacute;a</p>
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Elige c&oacute;mo registrar la categor&iacute;a</h2>
                        <p class="text-sm text-[#0b1b57]/70">
                            Usa una categor&iacute;a existente si ya aparece en el sistema. Si no est&aacute; disponible, reg&iacute;strala como nueva.
                        </p>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">
                        @if ($categories->isNotEmpty())
                            <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                                <input type="radio" name="modo_categoria" value="existente" class="mt-1" @checked($categoryMode === 'existente')>
                                <span>
                                    <span class="block font-semibold">Usar categor&iacute;a existente</span>
                                    <span class="block text-[#0b1b57]/70">Selecciona una categor&iacute;a ya registrada.</span>
                                </span>
                            </label>
                        @endif

                        <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                            <input type="radio" name="modo_categoria" value="nueva" class="mt-1" @checked($categoryMode === 'nueva' || $categories->isEmpty())>
                            <span>
                                <span class="block font-semibold">Registrar categor&iacute;a nueva</span>
                                <span class="block text-[#0b1b57]/70">Escribe el nombre y la descripci&oacute;n si a&uacute;n no existe.</span>
                            </span>
                        </label>
                    </div>

                    @error('modo_categoria')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($categories->isEmpty())
                        <p class="mt-4 text-sm text-[#0b1b57]/70">A&uacute;n no hay categor&iacute;as registradas, as&iacute; que se guardar&aacute; una categor&iacute;a nueva.</p>
                    @endif

                    <div class="mt-5 space-y-5">
                        <div data-mode-panel="categoria" data-mode-value="existente" class="{{ $categoryMode === 'existente' && $categories->isNotEmpty() ? '' : 'hidden' }}">
                            <label for="id_categoria" class="text-sm font-medium text-[#0b1b57]">Categor&iacute;a existente</label>
                            <p class="mt-1 text-xs text-[#0b1b57]/60">Selecciona una categor&iacute;a ya disponible.</p>
                            <select
                                id="id_categoria"
                                name="id_categoria"
                                class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                @disabled($categories->isEmpty())
                            >
                                <option value="">Selecciona una categor&iacute;a</option>
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

                        <div data-mode-panel="categoria" data-mode-value="nueva" class="{{ $categoryMode === 'nueva' || $categories->isEmpty() ? '' : 'hidden' }}">
                            <div class="grid gap-5 lg:grid-cols-2">
                                <div>
                                    <label for="nueva_categoria" class="text-sm font-medium text-[#0b1b57]">Nueva categor&iacute;a</label>
                                    <p class="mt-1 text-xs text-[#0b1b57]/60">Escribe el nombre de la categor&iacute;a nueva.</p>
                                    <input
                                        id="nueva_categoria"
                                        name="nueva_categoria"
                                        type="text"
                                        value="{{ old('nueva_categoria') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: Analg&eacute;sicos"
                                    >
                                    @error('nueva_categoria')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="descripcion_categoria" class="text-sm font-medium text-[#0b1b57]">Descripci&oacute;n de categor&iacute;a</label>
                                    <p class="mt-1 text-xs text-[#0b1b57]/60">Opcional. Describe brevemente esta categor&iacute;a.</p>
                                    <input
                                        id="descripcion_categoria"
                                        name="descripcion_categoria"
                                        type="text"
                                        value="{{ old('descripcion_categoria') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: Medicamentos para el alivio del dolor"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-2 border border-[#0b1b57]/10 bg-[#f8faff] p-5">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Presentaci&oacute;n</p>
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Elige c&oacute;mo registrar la presentaci&oacute;n</h2>
                        <p class="text-sm text-[#0b1b57]/70">
                            Usa una presentaci&oacute;n existente si ya la encuentras. Si todav&iacute;a no est&aacute;, puedes registrarla aqu&iacute; mismo.
                        </p>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-2">
                        @if ($presentations->isNotEmpty())
                            <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                                <input type="radio" name="modo_presentacion" value="existente" class="mt-1" @checked($presentationMode === 'existente')>
                                <span>
                                    <span class="block font-semibold">Usar presentaci&oacute;n existente</span>
                                    <span class="block text-[#0b1b57]/70">Selecciona una presentaci&oacute;n ya registrada.</span>
                                </span>
                            </label>
                        @endif

                        <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                            <input type="radio" name="modo_presentacion" value="nueva" class="mt-1" @checked($presentationMode === 'nueva' || $presentations->isEmpty())>
                            <span>
                                <span class="block font-semibold">Registrar presentaci&oacute;n nueva</span>
                                <span class="block text-[#0b1b57]/70">Escribe el nombre y una descripci&oacute;n si a&uacute;n no existe.</span>
                            </span>
                        </label>
                    </div>

                    @error('modo_presentacion')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($presentations->isEmpty())
                        <p class="mt-4 text-sm text-[#0b1b57]/70">A&uacute;n no hay presentaciones registradas, as&iacute; que se guardar&aacute; una presentaci&oacute;n nueva.</p>
                    @endif

                    <div class="mt-5 space-y-5">
                        <div data-mode-panel="presentacion" data-mode-value="existente" class="{{ $presentationMode === 'existente' && $presentations->isNotEmpty() ? '' : 'hidden' }}">
                            <label for="id_presentacion" class="text-sm font-medium text-[#0b1b57]">Presentaci&oacute;n existente</label>
                            <p class="mt-1 text-xs text-[#0b1b57]/60">Selecciona una presentaci&oacute;n disponible.</p>
                            <select
                                id="id_presentacion"
                                name="id_presentacion"
                                class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                @disabled($presentations->isEmpty())
                            >
                                <option value="">Selecciona una presentaci&oacute;n</option>
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

                        <div data-mode-panel="presentacion" data-mode-value="nueva" class="{{ $presentationMode === 'nueva' || $presentations->isEmpty() ? '' : 'hidden' }}">
                            <div class="grid gap-5 lg:grid-cols-2">
                                <div>
                                    <label for="nueva_presentacion" class="text-sm font-medium text-[#0b1b57]">Nueva presentaci&oacute;n</label>
                                    <p class="mt-1 text-xs text-[#0b1b57]/60">Escribe el nombre de la presentaci&oacute;n.</p>
                                    <input
                                        id="nueva_presentacion"
                                        name="nueva_presentacion"
                                        type="text"
                                        value="{{ old('nueva_presentacion') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: Caja"
                                    >
                                    @error('nueva_presentacion')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="descripcion_presentacion" class="text-sm font-medium text-[#0b1b57]">Descripci&oacute;n de presentaci&oacute;n</label>
                                    <p class="mt-1 text-xs text-[#0b1b57]/60">Opcional. Describe brevemente la presentaci&oacute;n.</p>
                                    <input
                                        id="descripcion_presentacion"
                                        name="descripcion_presentacion"
                                        type="text"
                                        value="{{ old('descripcion_presentacion') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: Caja con 30 tabletas"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="stock_minimo" class="text-sm font-medium text-[#0b1b57]">Stock m&iacute;nimo</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Cantidad m&iacute;nima antes de mostrar la alerta de reabastecimiento.</p>
                    <input
                        id="stock_minimo"
                        name="stock_minimo"
                        type="number"
                        min="1"
                        value="{{ old('stock_minimo') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="Ej: 10"
                    >
                    @error('stock_minimo')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado" class="text-sm font-medium text-[#0b1b57]">Estado del medicamento</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Define si el medicamento quedar&aacute; disponible para compras.</p>
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
                        href="{{ route('farmacia.medicines.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <script>
            (() => {
                const updatePanels = (group, value) => {
                    document.querySelectorAll(`[data-mode-panel="${group}"]`).forEach((panel) => {
                        const isActive = panel.dataset.modeValue === value;
                        panel.classList.toggle('hidden', !isActive);

                        panel.querySelectorAll('input, select').forEach((field) => {
                            field.disabled = !isActive;
                        });
                    });
                };

                const attachGroup = (fieldName, panelGroup, fallbackValue) => {
                    const radios = document.querySelectorAll(`input[name="${fieldName}"]`);

                    if (!radios.length) {
                        return;
                    }

                    const currentValue = Array.from(radios).find((radio) => radio.checked)?.value ?? fallbackValue;
                    updatePanels(panelGroup, currentValue);

                    radios.forEach((radio) => {
                        radio.addEventListener('change', () => updatePanels(panelGroup, radio.value));
                    });
                };

                attachGroup('modo_categoria', 'categoria', '{{ $categoryMode }}');
                attachGroup('modo_presentacion', 'presentacion', '{{ $presentationMode }}');
            })();
        </script>
    </section>
</x-layouts.panel>
