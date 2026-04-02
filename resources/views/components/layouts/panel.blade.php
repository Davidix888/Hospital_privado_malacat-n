<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Panel' }} | {{ config('app.name', 'Hospital Privado Malacatan') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f3f6fb] font-sans text-[#0b1b57]">
        <div class="min-h-screen">
            <header class="border-b border-[#0b1b57]/10 bg-[#0b1b57] text-white">
                <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
                    <div class="flex items-center gap-4">
                        <img
                            src="{{ asset('imagenes/Logo_Hospital.jpeg') }}"
                            alt="Logo Hospital Privado Malacatan"
                            class="h-14 w-14 rounded-2xl bg-white object-contain p-1.5 shadow-sm"
                        >
                        <div>
                            <p class="font-['Outfit'] text-xl font-semibold tracking-wide">Hospital Privado Malacatan</p>
                            <p class="text-sm text-white/75">Modulo administrativo</p>
                        </div>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-xl border border-white/20 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/10"
                        >
                            Cerrar sesion
                        </button>
                    </form>
                </div>
            </header>

            <main class="mx-auto max-w-7xl px-5 pb-10 pt-16 sm:px-12 sm:pb-12 sm:pt-20">
                {{ $slot }}
            </main>
        </div>
    </body>
</html>
