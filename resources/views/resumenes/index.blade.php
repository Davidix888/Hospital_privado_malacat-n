<x-layouts.panel title="Resúmenes">
    <section style="padding-top: 1.25rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">M&oacute;dulo anal&iacute;tico</p>
            <h1 class="mt-3 font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Res&uacute;menes y exportaci&oacute;n</h1>
            <p class="mt-4 max-w-3xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                Aqu&iacute; quedar&aacute; la vista de res&uacute;menes para los licenciados y otros roles autorizados. La exportaci&oacute;n ya
                queda protegida por permiso para conectarla despu&eacute;s a Excel o PDF.
            </p>

            @can('resumenes.export')
                <div class="pt-6">
                    <a
                        href="{{ route('resumenes.export') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Probar exportaci&oacute;n
                    </a>
                </div>
            @endcan
        </div>
    </section>
</x-layouts.panel>
