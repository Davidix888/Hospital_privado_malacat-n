<x-layouts.panel title="Panel principal">
    <section class="pt-8 sm:pt-10">
        <div class="mt-4 border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Panel principal</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">
                        Bienvenido, {{ auth()->user()->username }}
                    </h1>
                    <p class="max-w-2xl text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Este panel principal deja visible unicamente el acceso al modulo de usuarios para mantener una
                        interfaz mas limpia mientras seguimos desarrollando el sistema.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.2rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57]">
                    Perfil actual: <span class="font-semibold">Administrador</span>
                </div>
            </div>
        </div>

        <div class="border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 3.5rem;">
            <div class="grid gap-6 lg:items-center">
                <div class="space-y-3 pt-6 sm:pt-8">
                    <h2 class="font-['Outfit'] text-3xl font-semibold text-[#0b1b57]">Gestion de usuarios</h2>
                    <p class="max-w-2xl text-sm leading-7 text-[#0b1b57]/70 sm:text-base">
                        Ingresa al modulo para visualizar las acciones de crear usuario, editar usuario, listar y buscar,
                        asi como desactivar accesos del sistema.
                    </p>
                </div>

                <div class="pt-4">
                    <a
                        href="{{ route('usuarios.index') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar al modulo
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-layouts.panel>
