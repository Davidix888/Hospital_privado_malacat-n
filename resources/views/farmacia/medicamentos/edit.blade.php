<x-layouts.panel title="Editar catalogo">
    <section style="padding-top: 2rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Editar catalogo</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Actualiza el precio de venta, el stock minimo y la disponibilidad del medicamento seleccionado.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.medicines.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver al catalogo
                    </a>
                </div>
            </div>

            @include('farmacia.compras.partials.navigation', ['current' => 'medicines.index'])

            <div class="mt-8 grid gap-6 lg:grid-cols-2">
                <div class="border border-[#0b1b57]/10 bg-[#f8faff] p-5">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Referencia</p>
                    <div class="mt-4 space-y-3 text-sm text-[#0b1b57]">
                        <p><span class="font-semibold">Medicamento:</span> {{ $medicinePresentation->medicine?->nombre ?? 'Sin medicamento' }}</p>
                        <p><span class="font-semibold">Categoria:</span> {{ $medicinePresentation->medicine?->category?->nombre ?? 'Sin categoria' }}</p>
                        <p><span class="font-semibold">Presentacion:</span> {{ $medicinePresentation->presentation?->nombre ?? 'Sin presentacion' }}</p>
                    </div>
                </div>

                <form method="POST" action="{{ route('farmacia.medicines.update', $medicinePresentation) }}" class="grid gap-5 border border-[#0b1b57]/10 bg-white p-5">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="precio_venta" class="text-sm font-medium text-[#0b1b57]">Precio de venta</label>
                        <p class="mt-1 text-xs text-[#0b1b57]/60">Actualiza el precio al que se vendera este medicamento.</p>
                        <input
                            id="precio_venta"
                            name="precio_venta"
                            type="number"
                            step="0.01"
                            min="0.01"
                            value="{{ old('precio_venta', number_format((float) $medicinePresentation->precio_venta, 2, '.', '')) }}"
                            class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        >
                        @error('precio_venta')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="stock_minimo" class="text-sm font-medium text-[#0b1b57]">Stock minimo</label>
                        <p class="mt-1 text-xs text-[#0b1b57]/60">Cantidad minima antes de mostrar la alerta de reabastecimiento.</p>
                        <input
                            id="stock_minimo"
                            name="stock_minimo"
                            type="number"
                            min="1"
                            value="{{ old('stock_minimo', $medicinePresentation->stock_minimo) }}"
                            class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        >
                        @error('stock_minimo')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="estado" class="text-sm font-medium text-[#0b1b57]">Disponibilidad</label>
                        <p class="mt-1 text-xs text-[#0b1b57]/60">Define si este medicamento seguira disponible para compras y ventas.</p>
                        <select
                            id="estado"
                            name="estado"
                            class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        >
                            <option value="1" @selected((string) old('estado', (int) $medicinePresentation->estado) === '1')>Disponible</option>
                            <option value="0" @selected((string) old('estado', (int) $medicinePresentation->estado) === '0')>No disponible</option>
                        </select>
                        @error('estado')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button
                            type="submit"
                            class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                        >
                            Guardar cambios
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
        </div>
    </section>
</x-layouts.panel>
