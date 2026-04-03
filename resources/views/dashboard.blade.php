<x-layouts.panel title="Panel principal">
    <section style="padding-top: 1.25rem;">
        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 0.75rem;">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Panel principal</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">
                        Bienvenido, {{ auth()->user()->username }}
                    </h1>
                    <p class="max-w-2xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Este panel muestra unicamente los modulos habilitados para tu rol, para mantener un acceso
                        claro y controlado dentro del sistema.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.2rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57]">
                    Perfil actual:&nbsp;<span class="font-semibold">{{ auth()->user()->role_name }}</span>
                </div>
            </div>
        </div>

        @if ($modules->isNotEmpty())
            <div class="grid gap-6 md:grid-cols-2 xl:grid-cols-3" style="margin-top: 1.50rem; margin-bottom: 1.50rem;">
                @foreach ($modules as $module)
                    <article class="flex min-h-[260px] flex-col border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
                        <div class="space-y-3">
                            <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">{{ $module['badge'] }}</p>
                            <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">{{ $module['title'] }}</h2>
                            <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                                {{ $module['description'] }}
                            </p>
                        </div>

                        <div class="mt-auto pt-5">
                            <a
                                href="{{ route($module['route']) }}"
                                class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                            >
                                {{ $module['button'] }}
                            </a>
                        </div>
                    </article>
                @endforeach
            </div>
        @else
            <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 1.50rem; margin-bottom: 1.50rem;">
                <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Sin modulos disponibles</h2>
                <p class="mt-3 max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                    Tu rol todavia no tiene modulos habilitados. Cuando se te asigne un permiso, aqui aparecera el acceso.
                </p>
            </div>
        @endif
    </section>
</x-layouts.panel>
