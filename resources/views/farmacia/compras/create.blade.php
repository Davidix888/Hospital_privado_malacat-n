<x-layouts.panel title="Registrar compra">
    <section style="padding-top: 2rem;">
        @if (session('error') || $errors->any())
            <div
                id="purchase-create-error-popup"
                class="fixed right-6 top-24 z-50 max-w-sm border border-red-200 bg-red-50 px-5 py-4 text-sm text-red-700 shadow-xl"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold">No se pudo registrar la compra</p>
                        <p>{{ session('error', 'Revisa los datos del formulario y corrige los campos marcados.') }}</p>
                    </div>
                    <button
                        type="button"
                        class="text-lg font-semibold leading-none text-red-700/70 transition hover:text-red-700"
                        onclick="document.getElementById('purchase-create-error-popup')?.remove()"
                    >
                        ×
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('purchase-create-error-popup')?.remove(), 6000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Registrar compra</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Registra el proveedor, medicamento, cantidad, precio, lote y fechas. Si la compra queda pendiente,
                        el inventario no se actualiza hasta marcarla como entregada.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.purchases.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver a compras
                    </a>
                </div>
            </div>

            @include('farmacia.compras.partials.navigation', ['current' => 'purchases.create'])

            @if ($suppliers->isEmpty() || $medicinePresentations->isEmpty())
                <div class="mt-8 border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-700">
                    <p class="font-semibold">Antes de registrar una compra, completa los datos base del m&oacute;dulo.</p>
                    <div class="mt-2 space-y-1">
                        @if ($suppliers->isEmpty())
                            <p>Debes registrar al menos un proveedor activo.</p>
                        @endif

                        @if ($medicinePresentations->isEmpty())
                            <p>Debes registrar al menos un medicamento con presentaci&oacute;n activa.</p>
                        @endif
                    </div>
                    <div class="mt-4 flex flex-wrap gap-3">
                        @if ($suppliers->isEmpty())
                            <a
                                href="{{ route('farmacia.suppliers.create') }}"
                                class="inline-flex items-center justify-center rounded-[1rem] border border-amber-300 px-4 py-2 font-semibold text-amber-800 transition hover:bg-amber-100"
                            >
                                Registrar proveedor
                            </a>
                        @endif

                        @if ($medicinePresentations->isEmpty())
                            <a
                                href="{{ route('farmacia.medicines.create') }}"
                                class="inline-flex items-center justify-center rounded-[1rem] border border-amber-300 px-4 py-2 font-semibold text-amber-800 transition hover:bg-amber-100"
                            >
                                Registrar medicamento
                            </a>
                        @endif
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('farmacia.purchases.store') }}" class="mt-8 grid gap-6 lg:grid-cols-2">
                @csrf

                <div>
                    <label for="id_proveedor" class="text-sm font-medium text-[#0b1b57]">Proveedor</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Selecciona el proveedor que entreg&oacute; los insumos.</p>
                    <select
                        id="id_proveedor"
                        name="id_proveedor"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        @disabled($suppliers->isEmpty())
                    >
                        <option value="">Selecciona un proveedor</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id_proveedor }}" @selected(old('id_proveedor') == $supplier->id_proveedor)>
                                {{ $supplier->nombre }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_proveedor')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha" class="text-sm font-medium text-[#0b1b57]">Fecha y hora de compra</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Registra la fecha real en la que ingres&oacute; la compra.</p>
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

                <div>
                    <label for="id_medicamento_presentacion" class="text-sm font-medium text-[#0b1b57]">Medicamento y presentaci&oacute;n</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Elige el tipo de medicamento y su presentaci&oacute;n.</p>
                    <select
                        id="id_medicamento_presentacion"
                        name="id_medicamento_presentacion"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        @disabled($medicinePresentations->isEmpty())
                    >
                        <option value="">Selecciona una opci&oacute;n</option>
                        @foreach ($medicinePresentations as $item)
                            <option value="{{ $item->id_medicamento_presentacion }}" @selected(old('id_medicamento_presentacion') == $item->id_medicamento_presentacion)>
                                {{ $item->display_name }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_medicamento_presentacion')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="cantidad" class="text-sm font-medium text-[#0b1b57]">Cantidad ingresada</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa la cantidad de unidades que entran al stock.</p>
                    <input
                        id="cantidad"
                        name="cantidad"
                        type="number"
                        min="1"
                        value="{{ old('cantidad') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('cantidad')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="precio_compra" class="text-sm font-medium text-[#0b1b57]">Precio de compra</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Ingresa el precio unitario de compra.</p>
                    <input
                        id="precio_compra"
                        name="precio_compra"
                        type="number"
                        step="0.01"
                        min="0.01"
                        value="{{ old('precio_compra') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                        placeholder="Ejemplo: 25.50"
                    >
                    @error('precio_compra')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="estado_entrega" class="text-sm font-medium text-[#0b1b57]">Estado de entrega</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Define si la compra ya fue entregada o si todav&iacute;a est&aacute; pendiente.</p>
                    <select
                        id="estado_entrega"
                        name="estado_entrega"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                        <option value="entregada" @selected(old('estado_entrega', 'entregada') === 'entregada')>Entregada</option>
                        <option value="pendiente" @selected(old('estado_entrega') === 'pendiente')>Pendiente de entrega</option>
                    </select>
                    @error('estado_entrega')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="numero_lote" class="text-sm font-medium text-[#0b1b57]">Numero de lote</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Identificador del lote ingresado al inventario.</p>
                    <input
                        id="numero_lote"
                        name="numero_lote"
                        type="text"
                        value="{{ old('numero_lote') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('numero_lote')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha_ingreso" class="text-sm font-medium text-[#0b1b57]">Fecha de ingreso</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">D&iacute;a en que el lote se agreg&oacute; al inventario.</p>
                    <input
                        id="fecha_ingreso"
                        name="fecha_ingreso"
                        type="date"
                        value="{{ old('fecha_ingreso', now()->format('Y-m-d')) }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('fecha_ingreso')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="fecha_vencimiento" class="text-sm font-medium text-[#0b1b57]">Fecha de vencimiento</label>
                    <p class="mt-1 text-xs text-[#0b1b57]/60">Debe ser igual o posterior a la fecha de ingreso.</p>
                    <input
                        id="fecha_vencimiento"
                        name="fecha_vencimiento"
                        type="date"
                        value="{{ old('fecha_vencimiento') }}"
                        class="mt-2 w-full border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    >
                    @error('fecha_vencimiento')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="lg:col-span-2 border border-[#0b1b57]/10 bg-[#f8faff] px-4 py-3 text-sm text-[#0b1b57]/80">
                    Si la compra queda <span class="font-semibold">entregada</span>, se activar&aacute; y entrar&aacute; al inventario de inmediato.
                    Si queda <span class="font-semibold">pendiente</span>, se guardar&aacute; sin stock hasta que la marques como entregada.
                </div>

                <div class="flex gap-3 lg:col-span-2">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                        @disabled($suppliers->isEmpty() || $medicinePresentations->isEmpty())
                    >
                        Guardar compra
                    </button>
                    <a
                        href="{{ route('farmacia.purchases.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Cancelar
                    </a>
                </div>
            </form>
        </div>
    </section>
</x-layouts.panel>
