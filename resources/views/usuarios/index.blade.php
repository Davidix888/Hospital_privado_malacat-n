<x-layouts.panel title="Modulo de usuarios">
    <section style="padding-top: 2rem;">
        <div class="rounded-[3.2rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 1rem;">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="max-w-2xl space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Gestión de usuarios</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">
                        Menu de administracion de usuarios
                    </h1>
                    <p class="text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Desde aquí se pueden crear, buscar, actualizar y desactivar usuarios segun las reglas del sistema.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.4rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57] shadow-sm">
                    Usuario actual:&nbsp;<span class="font-semibold">{{ auth()->user()->username }}</span>
                </div>
            </div>
        </div>

        <div class="grid md:grid-cols-2 xl:grid-cols-4" style="margin-top: 1.50rem; gap: 1rem;">
            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Ingresar usuario</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Registra un nuevo usuario, lo vincula a un empleado y le asigna el rol correspondiente.
                    </p>
                </div>
                <div style="margin-top: 0.50rem;"></div>
                <a
                    href="{{ route('usuarios.create') }}"
                    class="mt-3 self-start rounded-[1.6rem] bg-[#0b1b57] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                >
                    Ingresar
                </a>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Editar usuario</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Actualiza username, rol, estado y, si hace falta, asigna una nueva contraseña.
                    </p>
                </div>
                <div style="margin-top: 0.50rem;"></div>
                <a
                    href="{{ route('usuarios.list') }}"
                    class="mt-3 self-start rounded-[1.6rem] bg-[#0b1b57] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                >
                    Ingresar
                </a>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Listar y buscar</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Consulta usuarios por nombre, username, rol o estado con filtros de busqueda.
                    </p>
                </div>
                <div style="margin-top: 0.50rem;"></div>
                <a
                    href="{{ route('usuarios.list') }}"
                    class="mt-3 self-start rounded-[1.6rem] bg-[#0b1b57] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                >
                    Ingresar
                </a>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Desactivar usuario</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Inhabilita accesos sin perder el historial del usuario dentro del sistema.
                    </p>
                </div>
                <div style="margin-top: 0.50rem;"></div>
                <a
                    href="{{ route('usuarios.deactivate.index') }}"
                    class="mt-3 self-start rounded-[1.6rem] bg-[#0b1b57] px-5 py-2.5 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                >
                    Ingresar
                </a>
            </article>
            <div style="margin-top: 0.75rem;"></div>
        </div>

    </section>
</x-layouts.panel>
