<x-layouts.panel title="Proveedores de farmacia">
    <section style="padding-top: 2rem;">
        @if (session('status'))
            <div
                id="supplier-success-popup"
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
                        onclick="document.getElementById('supplier-success-popup')?.remove()"
                    >
                        &times;
                    </button>
                </div>
            </div>
            <script>
                setTimeout(() => document.getElementById('supplier-success-popup')?.remove(), 5000);
            </script>
        @endif

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-4 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-start">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Gestion de proveedores</h1>
                    <p class="max-w-3xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Busca proveedores por nombre, correo o telefono y mantén a mano sus datos principales.
                    </p>
                </div>

                <div class="flex gap-3 lg:justify-end">
                    <a
                        href="{{ route('farmacia.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-[#0b1b57]/20 px-5 py-3 text-sm font-semibold text-[#0b1b57] transition hover:bg-[#0b1b57]/5"
                    >
                        Volver a farmacia
                    </a>
                    <a
                        href="{{ route('farmacia.suppliers.create') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Nuevo proveedor
                    </a>
                </div>
            </div>

            <form method="GET" action="{{ route('farmacia.suppliers.index') }}" class="mt-8 grid gap-4 lg:grid-cols-3">
                <input
                    name="q"
                    type="text"
                    value="{{ $filters['q'] ?? '' }}"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                    placeholder="Buscar por nombre, correo o telefono"
                >

                <select
                    name="estado"
                    class="border border-[#0b1b57]/15 bg-white px-4 py-3 text-sm text-[#0b1b57] outline-none"
                >
                    <option value="">Todos los estados</option>
                    <option value="activo" @selected(($filters['estado'] ?? '') === 'activo')>Activos</option>
                    <option value="inactivo" @selected(($filters['estado'] ?? '') === 'inactivo')>Inactivos</option>
                </select>

                <div class="flex gap-3">
                    <button
                        type="submit"
                        class="rounded-[1.2rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Buscar
                    </button>
                    <a
                        href="{{ route('farmacia.suppliers.index') }}"
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
                            <th class="w-[22%] px-4 py-3 text-center font-semibold align-middle">Proveedor</th>
                            <th class="w-[24%] px-4 py-3 text-center font-semibold align-middle">Direccion</th>
                            <th class="w-[20%] px-4 py-3 text-center font-semibold align-middle">Correo</th>
                            <th class="w-[18%] px-4 py-3 text-center font-semibold align-middle">Telefono</th>
                            <th class="w-[16%] px-4 py-3 text-center font-semibold align-middle">Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($suppliers as $supplier)
                            <tr class="border-t border-[#0b1b57]/10 bg-white">
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $supplier->nombre }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $supplier->direccion }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $supplier->email?->correo ?? 'Sin correo' }}</td>
                                <td class="px-4 py-3 text-center align-middle break-words">{{ $supplier->phone?->numero ?? 'Sin telefono' }}</td>
                                <td class="px-4 py-3 text-center align-middle">
                                    <span class="{{ $supplier->estado ? 'text-emerald-600' : 'text-red-600' }}">
                                        {{ $supplier->estado ? 'Activo' : 'Inactivo' }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-center text-[#0b1b57]/70">
                                    No hay proveedores registrados con los filtros indicados.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $suppliers->links() }}
            </div>
        </div>
    </section>
</x-layouts.panel>
