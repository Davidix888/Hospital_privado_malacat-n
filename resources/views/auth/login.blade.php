<x-layouts.auth title="Iniciar sesión">
    <section class="flex w-full items-center justify-center">
        <div class="w-full max-w-[520px] overflow-hidden rounded-[1.8rem] border border-[#0b1b57]/15 bg-white shadow-[0_22px_54px_rgba(11,27,87,0.13)]">
            <div class="px-5 pt-5 text-center sm:px-7 sm:pt-6">
                <img
                    src="{{ asset('imagenes/Logo_Hospital.jpeg') }}"
                    alt="Logo Hospital Privado Malacat&aacute;n"
                    class="mx-auto mb-3 w-full max-w-[190px] object-contain sm:max-w-[240px]"
                >
                <div class="mx-auto my-4 h-px w-full max-w-[360px] bg-[#0b1b57]/10"></div>
                <h1 class="font-['Outfit'] text-3xl font-bold text-[#0b1b57] sm:text-[2.2rem]">Inicio de sesi&oacute;n</h1>
            </div>

            <div class="px-5 py-5 sm:px-7 sm:py-6">
                <p class="mb-4 text-center text-sm leading-6 text-[#0b1b57]/70">
                    Ingrese sus datos
                </p>

                @if (session('status'))
                    <div class="mb-4 rounded-2xl border border-[#0b1b57]/15 bg-[#eef3fb] px-4 py-2.5 text-sm text-[#0b1b57]">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login.store') }}" class="grid gap-4">
                    @csrf

                    <div>
                        <label for="username" class="mb-1.5 block text-sm font-semibold text-[#0b1b57]">Usuario</label>
                        <input
                            id="username"
                            type="text"
                            name="username"
                            value="{{ old('username') }}"
                            required
                            autofocus
                            autocomplete="username"
                            placeholder="Usuario"
                            class="w-full rounded-2xl border border-[#0b1b57]/20 bg-[#f9fbff] px-4 py-3 text-sm text-[#0b1b57] outline-none transition focus:border-[#0b1b57] focus:ring-4 focus:ring-[#0b1b57]/10"
                        >
                        @error('username')
                            <p class="mt-2 text-[0.92rem] text-[#d71920]">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password" class="mb-1.5 block text-sm font-semibold text-[#0b1b57]">Contrase&ntilde;a</label>
                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                            placeholder="Contrase&ntilde;a"
                            class="w-full rounded-2xl border border-[#0b1b57]/20 bg-[#f9fbff] px-4 py-3 text-sm text-[#0b1b57] outline-none transition focus:border-[#0b1b57] focus:ring-4 focus:ring-[#0b1b57]/10"
                        >
                        @error('password')
                            <p class="mt-2 text-[0.92rem] text-[#d71920]">{{ $message }}</p>
                        @enderror
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-[#0b1b57] px-[18px] py-3 text-sm font-bold text-white transition hover:-translate-y-0.5 hover:bg-[#10256f] hover:shadow-[0_14px_24px_rgba(11,27,87,0.18)]"
                    >
                        Ingresar
                    </button>
                </form>
            </div>
        </div>
    </section>
</x-layouts.auth>
