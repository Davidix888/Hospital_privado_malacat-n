<x-layouts.panel title="Modulo de farmacia">
    <section style="padding-top: 2rem;">
        <div class="rounded-[3.2rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="max-w-3xl space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Menu operativo de farmacia</h1>
                    <p class="text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Este modulo se enfoca en la compra de insumos y el control del inventario usando las tablas reales
                        del sistema de farmacia.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.4rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57] shadow-sm">
                    Usuario actual:&nbsp;<span class="font-semibold">{{ auth()->user()->username }}</span>
                </div>
            </div>
        </div>

        @if ($lowStockAlerts->isNotEmpty())
            <div class="border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 shadow-sm" style="margin-top: 1rem;">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-2">
                        <p class="font-semibold">Notificacion de abastecimiento bajo</p>
                        <p>
                            Hay medicamentos que ya alcanzaron su stock minimo o estan por debajo del nivel permitido.
                            Debe considerarse una nueva compra de insumos.
                        </p>
                        <div class="space-y-1">
                            @foreach ($lowStockAlerts as $alert)
                                <p>
                                    {{ $alert->medicine_name }} - {{ $alert->presentation_name }}:
                                    stock actual {{ $alert->cantidad_actual }},
                                    stock minimo {{ $alert->stock_minimo }}.
                                </p>
                            @endforeach
                        </div>
                    </div>

                    <a
                        href="{{ route('farmacia.inventory.index') }}"
                        class="inline-flex h-fit items-center justify-center whitespace-nowrap rounded-[1.2rem] border border-amber-300 px-5 py-3 font-semibold text-amber-800 transition hover:bg-amber-100"
                    >
                        Ver inventario
                    </a>
                </div>
            </div>
        @endif

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3" style="margin-top: 1.50rem;">
            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de catalogo</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Catalogo de medicamentos</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Registra medicamentos, categorias, presentaciones, precio de venta y stock minimo para dejarlos listos.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.medicines.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de proveedores</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Gestion de proveedores</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Registra y busca proveedores para mantener disponibles sus datos de contacto dentro del modulo.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.suppliers.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de compra</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Registrar compra</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Registra el ingreso de medicamentos, su cantidad, lote, precio de compra y actualiza el stock.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.purchases.create') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de compra</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Compras registradas</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Visualiza compras registradas, proveedor, usuario responsable, fecha, total y cantidad de
                        unidades compradas.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.purchases.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de inventario</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Inventario de farmacia</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Consulta lotes, medicamento, presentacion, stock actual, stock minimo, fecha de vencimiento y
                        alertas de bajo inventario.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.inventory.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>
        </div>
        <div style="height: 1rem;"></div>

    </section>
</x-layouts.panel>
