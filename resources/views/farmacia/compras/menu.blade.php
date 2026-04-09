<x-layouts.panel title="Modulo de compras">
    <section style="padding-top: 1.25rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 0.75rem;">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Compras</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">
                        Menu de compras de farmacia
                    </h1>
                    <p class="max-w-2xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Centraliza las tareas que permiten abastecer la farmacia: cat&aacute;logo, proveedores,
                        registro de compras e historial de ingresos.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.2rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57]">
                    Perfil actual:&nbsp;<span class="font-semibold">{{ auth()->user()->role_name }}</span>
                </div>
            </div>
        </div>

        <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-4" style="margin-top: 1.50rem; margin-bottom: 1.50rem;">
            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de catalogo</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Catalogo de medicamentos</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Registra medicamentos, categor&iacute;as, presentaciones, precio de venta y stock m&iacute;nimo.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.medicines.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>

            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de proveedores</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Gestion de proveedores</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Registra y busca proveedores para mantener listos sus datos de contacto.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.suppliers.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>

            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de compra</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Registrar compra</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Registra el ingreso de medicamentos, su cantidad, lote, precio de compra y actualiza el stock.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.purchases.create') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>

            <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Modulo de compra</p>
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Compras registradas</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Visualiza compras registradas, proveedor, usuario responsable, fecha, total y cantidad de unidades compradas.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('farmacia.purchases.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al m&oacute;dulo
                    </a>
                </div>
            </article>
        </div>
    </section>
</x-layouts.panel>
