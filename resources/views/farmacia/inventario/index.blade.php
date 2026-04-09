<x-layouts.panel title="Inventario de farmacia">
    <section style="padding-top: 2rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Inventario</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Visualiza existencias por lote, medicamento, presentacion y fechas de vencimiento para
                        identificar rapidamente niveles bajos de stock.
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

            @if ($lowStockAlerts->isNotEmpty())
                <div class="mt-8 border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800">
                    <div class="space-y-2">
                        <p class="font-semibold">Alerta de stock minimo</p>
                        <p>
                            Los siguientes medicamentos necesitan reabastecimiento porque su cantidad actual ya alcanzo
                            o bajo del stock minimo configurado.
                        </p>
                        <div class="space-y-1">
                            @foreach ($lowStockAlerts as $alert)
                                <p>
                                    {{ $alert->medicine_name }} - {{ $alert->presentation_name }}:
                                    actual {{ $alert->cantidad_actual }},
                                    minimo {{ $alert->stock_minimo }},
                                    lote {{ $alert->lot_number }}.
                                </p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <form method="GET" action="{{ route('farmacia.inventory.index') }}" class="mt-8 grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto]">
                <input
                    name="q"
                    type="text"
                    value="{{ $filters['q'] ?? '' }}"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    placeholder="Buscar por medicamento, presentacion o lote"
                >

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Buscar
                    </button>
                    <a
                        href="{{ route('farmacia.inventory.index') }}"
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
                            <th class="w-[20%] px-4 py-3 text-center font-semibold align-middle">Medicamento</th>
                            <th class="w-[14%] px-4 py-3 text-center font-semibold align-middle">Presentacion</th>
                            <th class="w-[14%] px-4 py-3 text-center font-semibold align-middle">Lote</th>
                            <th class="w-[12%] px-4 py-3 text-center font-semibold align-middle">Stock actual</th>
                            <th class="w-[12%] px-4 py-3 text-center font-semibold align-middle">Stock minimo</th>
                            <th class="w-[14%] px-4 py-3 text-center font-semibold align-middle">Vence</th>
                            <th class="w-[14%] px-4 py-3 text-center font-semibold align-middle">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($inventory as $item)
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $item->medicine_name }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $item->presentation_name }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $item->lot_number }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $item->cantidad_actual }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $item->stock_minimo }}</td>
                                <td class="px-4 py-3 text-center align-middle">{{ $item->expires_at ?? 'Sin fecha' }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <span class="{{ $item->is_low_stock ? 'text-red-600' : 'text-emerald-600' }}">
                                        {{ $item->is_low_stock ? 'Bajo stock' : 'Disponible' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-6 text-center text-[#0b1b57]/70">
                                    No hay registros de inventario con los filtros indicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $inventory->links() }}
            </div>
        </div>
    </section>
</x-layouts.panel>
