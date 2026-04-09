<x-layouts.panel title="Modulo de farmacia">
    <section style="padding-top: 1.25rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 0.75rem;">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Farmacia</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">
                        Men&uacute; principal de farmacia
                    </h1>
                    <p class="max-w-2xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Organiza las operaciones de farmacia por procesos: compras, ventas, inventario y reportes,
                        para mantener un flujo m&aacute;s claro dentro del sistema.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.2rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57]">
                    Perfil actual:&nbsp;<span class="font-semibold">{{ auth()->user()->role_name }}</span>
                </div>
            </div>
        </div>

        @if ($lowStockAlerts->isNotEmpty())
            <div class="border border-amber-200 bg-amber-50 px-5 py-4 text-sm text-amber-800 shadow-sm" style="margin-top: 1rem;">
                <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
                    <div class="space-y-2">
                        <p class="font-semibold">Notificaci&oacute;n de abastecimiento bajo</p>
                        <p>
                            Hay medicamentos que ya alcanzaron su stock m&iacute;nimo o est&aacute;n por debajo del nivel permitido.
                            Debe considerarse una nueva compra de insumos.
                        </p>
                        <div class="space-y-1">
                            @foreach ($lowStockAlerts as $alert)
                                <p>
                                    {{ $alert->medicine_name }} - {{ $alert->presentation_name }}:
                                    stock actual {{ $alert->cantidad_actual }},
                                    stock m&iacute;nimo {{ $alert->stock_minimo }}.
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

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4" style="margin-top: 1.50rem; margin-bottom: 1.50rem;">
            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo operativo</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Compras</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Re&uacute;ne el cat&aacute;logo de medicamentos, la gesti&oacute;n de proveedores, el registro de compras y el historial de ingresos.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.purchases.menu') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>

            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo operativo</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Ventas</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Preparado para gestionar la salida de medicamentos, el registro de ventas y el descuento autom&aacute;tico del stock.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.sales.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>

            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo operativo</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Inventario</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Consulta lotes, stock actual, stock m&iacute;nimo, fechas de vencimiento y el impacto conjunto de compras y ventas.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.inventory.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>

            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de analisis</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Reportes</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Espacio destinado a reportes de compras, ventas e inventario para apoyar decisiones y seguimiento operativo.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.reports.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>
        </div>
    </section>
</x-layouts.panel>
