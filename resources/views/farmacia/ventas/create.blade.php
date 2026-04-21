<x-layouts.panel title="Registrar venta">
    <section style="padding-top: 2rem;">
        @php
            $patientMode = old('modo_paciente', 'ninguno');
        @endphp

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Registrar venta</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Registra la salida del medicamento seleccionando un lote con stock disponible. El paciente ahora es opcional.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.sales.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver a ventas
                    </a>
                    <a
                        href="{{ route('farmacia.inventory.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Ver inventario
                    </a>
                </div>
            </div>

            @if ($availableInventory->isEmpty())
                <div class="mt-8 border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    <p class="font-semibold">No hay lotes disponibles para vender.</p>
                    <p class="mt-2">
                        Solo se pueden vender lotes activos, con stock disponible y que no est&eacute;n vencidos.
                    </p>
                    <div class="mt-4 flex flex-wrap gap-3">
                        <a
                            href="{{ route('farmacia.inventory.index') }}"
                            class="inline-flex items-center justify-center rounded-[1rem] border border-amber-300 px-4 py-2 font-semibold text-amber-800 transition hover:bg-amber-100"
                        >
                            Revisar inventario
                        </a>
                        <a
                            href="{{ route('farmacia.purchases.create') }}"
                            class="inline-flex items-center justify-center rounded-[1rem] border border-amber-300 px-4 py-2 font-semibold text-amber-800 transition hover:bg-amber-100"
                        >
                            Registrar compra
                        </a>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('farmacia.sales.store') }}" class="mt-8 grid gap-6 lg:grid-cols-2">
                @csrf
                <input type="hidden" name="estado" value="1">

                <div class="lg:col-span-2 border border-[#0b1b57]/10 bg-[#f8faff] p-5">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Paso 1</p>
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Selecciona el medicamento a vender</h2>
                        <p class="text-sm text-[#0b1b57]/70">
                            Empieza por elegir el lote correcto. El sistema solo muestra lotes activos, con stock disponible y no vencidos.
                        </p>
                    </div>

                    <div class="mt-5">
                        <label for="id_lote" class="text-sm font-medium text-[#0b1b57]">Lote disponible</label>
                        <p class="mt-1 text-xs text-[#0b1b57]/60">
                            Revisa el medicamento, la presentaci&oacute;n, el stock y el precio antes de continuar.
                        </p>
                        <select
                            id="id_lote"
                            name="id_lote"
                            class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                            @disabled($availableInventory->isEmpty())
                        >
                            <option value="">Selecciona un lote disponible</option>
                            @foreach ($availableInventory as $item)
                                <option value="{{ $item->id_lote }}" @selected(old('id_lote') == $item->id_lote)>
                                    {{ $item->sales_option_label }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_lote')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="border border-[#0b1b57]/10 bg-white p-5">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Paso 2</p>
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Completa los datos de la venta</h2>
                        <p class="text-sm text-[#0b1b57]/70">
                            Indica cu&aacute;ntas unidades saldr&aacute;n del lote y registra la fecha real del despacho.
                        </p>
                    </div>

                    <div class="mt-5 grid gap-5">
                        <div>
                            <label for="cantidad" class="text-sm font-medium text-[#0b1b57]">Cantidad a vender</label>
                            <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa la cantidad de unidades que saldr&aacute;n del inventario.</p>
                            <input
                                id="cantidad"
                                name="cantidad"
                                type="number"
                                min="1"
                                value="{{ old('cantidad') }}"
                                class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                placeholder="Ej: 2"
                            >
                            @error('cantidad')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="fecha" class="text-sm font-medium text-[#0b1b57]">Fecha y hora de la venta</label>
                            <p class="mt-1 text-xs text-[#0b1b57]/60">Registra la fecha real en la que se despach&oacute; el medicamento.</p>
                            <input
                                id="fecha"
                                name="fecha"
                                type="datetime-local"
                                value="{{ old('fecha', now()->format('Y-m-d\\TH:i')) }}"
                                class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                            >
                            @error('fecha')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="border border-[#0b1b57]/10 bg-[#f8faff] px-4 py-5 text-sm text-[#0b1b57]/80">
                    <p class="font-semibold text-[#0b1b57]">Resumen r&aacute;pido</p>
                    <p class="mt-2">
                        El precio de la venta se toma autom&aacute;ticamente del cat&aacute;logo del medicamento y el stock se actualiza al guardar.
                    </p>
                </div>

                <div class="lg:col-span-2 border border-[#0b1b57]/10 bg-[#f8faff] p-5">
                    <div class="space-y-2">
                        <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Paso 3</p>
                        <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Paciente opcional</h2>
                        <p class="text-sm text-[#0b1b57]/70">
                            Si necesitas asociar la venta a un paciente, puedes elegir uno existente o registrar uno nuevo. Si no, la venta puede guardarse sin paciente.
                        </p>
                    </div>

                    <div class="mt-5 grid gap-3 md:grid-cols-3">
                        <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                            <input type="radio" name="modo_paciente" value="ninguno" class="mt-1" @checked($patientMode === 'ninguno')>
                            <span>
                                <span class="block font-semibold">Sin paciente</span>
                                <span class="block text-[#0b1b57]/70">Guarda la venta sin asociarla a un expediente.</span>
                            </span>
                        </label>

                        @if ($patients->isNotEmpty())
                            <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                                <input type="radio" name="modo_paciente" value="existente" class="mt-1" @checked($patientMode === 'existente')>
                                <span>
                                    <span class="block font-semibold">Usar paciente existente</span>
                                    <span class="block text-[#0b1b57]/70">Selecciona un paciente activo ya registrado.</span>
                                </span>
                            </label>
                        @endif

                        <label class="flex cursor-pointer items-start gap-3 border border-[#0b1b57]/10 bg-white px-4 py-3 text-sm text-[#0b1b57]">
                            <input type="radio" name="modo_paciente" value="nuevo" class="mt-1" @checked($patientMode === 'nuevo')>
                            <span>
                                <span class="block font-semibold">Registrar paciente nuevo</span>
                                <span class="block text-[#0b1b57]/70">Completa los datos b&aacute;sicos del paciente dentro de la misma venta.</span>
                            </span>
                        </label>
                    </div>

                    @error('modo_paciente')
                        <p class="mt-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror

                    @if ($patients->isEmpty())
                        <p class="mt-4 text-sm text-[#0b1b57]/70">No hay pacientes activos registrados. Si necesitas asociar la venta, puedes registrar uno nuevo aqui mismo.</p>
                    @endif

                    <div class="mt-5 space-y-5">
                        <div data-mode-panel="paciente" data-mode-value="existente" class="{{ $patientMode === 'existente' && $patients->isNotEmpty() ? '' : 'hidden' }}">
                            <label for="id_paciente" class="text-sm font-medium text-[#0b1b57]">Paciente existente</label>
                            <p class="mt-1 text-xs text-[#0b1b57]/60">Selecciona un paciente activo.</p>
                            <select
                                id="id_paciente"
                                name="id_paciente"
                                class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                @disabled($patients->isEmpty())
                            >
                                <option value="">Selecciona un paciente</option>
                                @foreach ($patients as $patient)
                                    <option value="{{ $patient->id_paciente }}" @selected(old('id_paciente') == $patient->id_paciente)>
                                        {{ $patient->full_name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('id_paciente')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div data-mode-panel="paciente" data-mode-value="nuevo" class="{{ $patientMode === 'nuevo' || $patients->isEmpty() ? '' : 'hidden' }}">
                            <div class="grid gap-5 lg:grid-cols-2">
                                <div>
                                    <label for="nombres_paciente" class="text-sm font-medium text-[#0b1b57]">Nombres del paciente</label>
                                    <input
                                        id="nombres_paciente"
                                        name="nombres_paciente"
                                        type="text"
                                        value="{{ old('nombres_paciente') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: Juan Carlos"
                                    >
                                    @error('nombres_paciente')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="apellidos_paciente" class="text-sm font-medium text-[#0b1b57]">Apellidos del paciente</label>
                                    <input
                                        id="apellidos_paciente"
                                        name="apellidos_paciente"
                                        type="text"
                                        value="{{ old('apellidos_paciente') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: M&eacute;ndez L&oacute;pez"
                                    >
                                    @error('apellidos_paciente')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="fecha_nacimiento_paciente" class="text-sm font-medium text-[#0b1b57]">Fecha de nacimiento</label>
                                    <input
                                        id="fecha_nacimiento_paciente"
                                        name="fecha_nacimiento_paciente"
                                        type="date"
                                        value="{{ old('fecha_nacimiento_paciente') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                    >
                                    @error('fecha_nacimiento_paciente')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="sexo_paciente" class="text-sm font-medium text-[#0b1b57]">Sexo</label>
                                    <select
                                        id="sexo_paciente"
                                        name="sexo_paciente"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                    >
                                        <option value="">Selecciona una opci&oacute;n</option>
                                        <option value="Masculino" @selected(old('sexo_paciente') === 'Masculino')>Masculino</option>
                                        <option value="Femenino" @selected(old('sexo_paciente') === 'Femenino')>Femenino</option>
                                        <option value="Otro" @selected(old('sexo_paciente') === 'Otro')>Otro</option>
                                    </select>
                                    @error('sexo_paciente')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="lg:col-span-2">
                                    <label for="direccion_paciente" class="text-sm font-medium text-[#0b1b57]">Direcci&oacute;n</label>
                                    <input
                                        id="direccion_paciente"
                                        name="direccion_paciente"
                                        type="text"
                                        value="{{ old('direccion_paciente') }}"
                                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                                        placeholder="Ej: Barrio El Centro, Malacat&aacute;n"
                                    >
                                    @error('direccion_paciente')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 lg:col-span-2">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                        @disabled($availableInventory->isEmpty())
                    >
                        Guardar venta
                    </button>
                    <a
                        href="{{ route('farmacia.sales.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>

        <script>
            (() => {
                const updatePanels = (value) => {
                    document.querySelectorAll('[data-mode-panel="paciente"]').forEach((panel) => {
                        const isActive = panel.dataset.modeValue === value;
                        panel.classList.toggle('hidden', !isActive);

                        panel.querySelectorAll('input, select').forEach((field) => {
                            field.disabled = !isActive;
                        });
                    });
                };

                const radios = document.querySelectorAll('input[name="modo_paciente"]');
                const currentValue = Array.from(radios).find((radio) => radio.checked)?.value ?? '{{ $patientMode }}';

                updatePanels(currentValue);

                radios.forEach((radio) => {
                    radio.addEventListener('change', () => updatePanels(radio.value));
                });
            })();
        </script>
    </section>
</x-layouts.panel>
