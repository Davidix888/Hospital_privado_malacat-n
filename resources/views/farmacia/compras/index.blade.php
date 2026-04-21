<x-layouts.panel title="Compras de farmacia">
    <section style="padding-top: 2rem;">
        @if (session('status'))
            <div
                id="pharmacy-success-popup"
                class="fixed right-6 top-24 z-50 max-w-sm border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-700 shadow-xl"
            >
                <div class="flex items-start justify-between gap-4">
                    <div class="space-y-1">
                        <p class="font-semibold">Operacion completada</p>
                        <p>{{ session('status') }}</p>
                    </div>
                    <button
                        type="button"
                        class="text-lg font-semibold leading-none text-emerald-700/70 transition hover:text-emerald-700"
                        onclick="document.getElementById('pharmacy-success-popup')?.remove()"
                    >
                        ×
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('pharmacy-success-popup')?.remove(), 5000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Compras de insumos</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Consulta las compras registradas con su estado de entrega, proveedor, usuario responsable, total
                        y volumen de unidades compradas.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.purchases.create') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Registrar compra
                    </a>
                </div>
            </div>

            @include('farmacia.compras.partials.navigation', ['current' => 'purchases.index'])

            <form method="GET" action="{{ route('farmacia.purchases.index') }}" class="mt-8 grid gap-4 lg:grid-cols-3">
                <input
                    name="q"
                    type="text"
                    value="{{ $filters['q'] ?? '' }}"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    placeholder="Buscar por proveedor, usuario, medicamento o lote"
                >

                <select
                    name="estado_entrega"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                >
                    <option value="">Todas las entregas</option>
                    <option value="entregada" @selected(($filters['estado_entrega'] ?? '') === 'entregada')>Entregadas</option>
                    <option value="pendiente" @selected(($filters['estado_entrega'] ?? '') === 'pendiente')>Pendientes</option>
                </select>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Buscar
                    </button>
                    <a
                        href="{{ route('farmacia.purchases.index') }}"
                        class="rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Limpiar
                    </a>
                </div>
            </form>

            <div class="mt-8 w-full overflow-x-auto">
                <table class="w-full table-fixed border border-[#0b1b57]/10 text-sm text-[#0b1b57]">
                    <thead class="bg-[#0b1b57] text-white">
                        <tr>
                            <th class="w-[12%] px-4 py-3 text-center font-semibold align-middle">Fecha</th>
                            <th class="w-[12%] px-4 py-3 text-center font-semibold align-middle">Entrega</th>
                            <th class="w-[15%] px-4 py-3 text-center font-semibold align-middle">Proveedor</th>
                            <th class="w-[18%] px-4 py-3 text-center font-semibold align-middle">Medicamento</th>
                            <th class="w-[12%] px-4 py-3 text-center font-semibold align-middle">Lote</th>
                            <th class="w-[11%] px-4 py-3 text-center font-semibold align-middle">Usuario</th>
                            <th class="w-[10%] px-4 py-3 text-center font-semibold align-middle">Cantidad</th>
                            <th class="w-[8%] px-4 py-3 text-center font-semibold align-middle">Total</th>
                            <th class="w-[7%] px-4 py-3 text-center font-semibold align-middle">Estado</th>
                            <th class="w-[7%] px-4 py-3 text-center font-semibold align-middle">Accion</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($purchases as $purchase)
                            @php
                                $detailLabels = $purchase->details
                                    ->map(fn ($detail) => $detail->medicinePresentation?->display_name)
                                    ->filter()
                                    ->unique()
                                    ->values();
                                $lotNumbers = $purchase->details
                                    ->flatMap(fn ($detail) => $detail->lots->pluck('numero_lote'))
                                    ->filter()
                                    ->unique()
                                    ->values();
                            @endphp
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 text-center align-middle">{{ optional($purchase->fecha)->format('d/m/Y H:i') }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <span class="{{ $purchase->delivery_text_class }}">
                                        {{ $purchase->delivery_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $purchase->supplier?->nombre ?? 'Sin proveedor' }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">
                                    {{ $detailLabels->isNotEmpty() ? $detailLabels->implode(', ') : 'Sin detalle registrado' }}
                                </td>
                                <td class="px-4 py-3 text-center align-middle break-words">
                                    {{ $lotNumbers->isNotEmpty() ? $lotNumbers->implode(', ') : 'Sin lote' }}
                                </td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $purchase->user?->username ?? 'Sin usuario' }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $purchase->items_count }}</td>
                                <td class="px-4 py-3 text-center align-middle">Q {{ number_format((float) $purchase->total, 2) }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <span class="{{ $purchase->estado ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $purchase->system_status_label }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center align-middle">
                                    @if (! $purchase->is_delivered)
                                        <form method="POST" action="{{ route('farmacia.purchases.deliver', $purchase) }}">
                                            @csrf
                                            @method('PATCH')
                                            <button
                                                type="submit"
                                                class="rounded-[1rem] border border-emerald-200 px-3 py-2 text-xs font-semibold text-emerald-700 transition hover:bg-emerald-50"
                                            >
                                                Marcar entrega
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-[#0b1b57]/50">Completada</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="10" class="px-4 py-6 text-center text-[#0b1b57]/70">
                                    No hay compras registradas con los filtros indicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $purchases->links() }}
            </div>
        </div>
    </section>
</x-layouts.panel>
