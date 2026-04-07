<x-layouts.panel title="Módulo de usuarios">
    <section style="padding-top: 2rem;">
        <div class="rounded-[3.2rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm sm:p-8" style="margin-top: 1rem;">
            <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_260px] lg:items-center">
                <div class="max-w-3xl space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.3em] text-[#d71920]">Usuarios</p>
                    <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-4xl">Menú operativo de usuarios</h1>
                    <p class="text-sm leading-6 text-[#0b1b57]/70 sm:text-base">
                        Este módulo permite registrar usuarios, actualizar sus datos, consultar el listado y controlar
                        su acceso dentro del sistema.
                    </p>
                </div>

                <div class="flex min-h-[132px] items-center rounded-[2.4rem] border border-[#0b1b57]/10 bg-[#f4f7fb] px-5 py-4 text-sm text-[#0b1b57] shadow-sm">
                    Usuario actual:&nbsp;<span class="font-semibold">{{ auth()->user()->username }}</span>
                </div>
            </div>
        </div>

        <div class="grid gap-4 md:grid-cols-2 xl:grid-cols-3" style="margin-top: 1.50rem;">
            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Módulo de registro</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Registrar usuario</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Registra un nuevo usuario, lo vincula a un empleado y le asigna el rol correspondiente.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('usuarios.create') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Módulo de edición</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Editar usuarios</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Actualiza el usuario, el rol, el estado y, si hace falta, asigna una nueva contraseña.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('usuarios.list') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Módulo de consulta</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Usuarios registrados</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Consulta usuarios por nombre, username, rol o estado con filtros de búsqueda.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('usuarios.list') }}"
                        class="inline-flex rounded-[1.6rem] bg-[#0b1b57] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#10256f]"
                    >
                        Ingresar
                    </a>
                </div>
            </article>

            <article class="flex min-h-[280px] flex-col rounded-[2.8rem] border border-[#0b1b57]/10 bg-white p-6 shadow-sm">
                <div class="space-y-3">
                    <p class="text-sm font-semibold uppercase tracking-[0.2em] text-[#d71920]">Módulo de accesos</p>
                    <h2 class="font-['Outfit'] text-2xl font-semibold text-[#0b1b57]">Activar y desactivar</h2>
                    <p class="text-sm leading-6 text-[#0b1b57]/70">
                        Gestiona el acceso de los usuarios sin perder su historial ni su información dentro del sistema.
                    </p>
                </div>

                <div class="mt-auto pt-5">
                    <a
                        href="{{ route('usuarios.deactivate.index') }}"
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
