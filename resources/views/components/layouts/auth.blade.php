<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Acceso seguro' }} | {{ config('app.name', 'Hospital Privado Malacatán') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:500,600,700" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f4f7fb] font-sans text-[#0b1b57]">
        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute left-0 top-0 h-32 w-32 rounded-br-[2.25rem] border-r border-b border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute right-0 top-0 h-32 w-32 rounded-bl-[2.25rem] border-l border-b border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute bottom-0 left-0 h-32 w-32 rounded-tr-[2.25rem] border-r border-t border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute bottom-0 right-0 h-32 w-32 rounded-tl-[2.25rem] border-l border-t border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute -left-20 top-1/3 h-44 w-44 rounded-full bg-[#d71920]/8 blur-3xl sm:h-52 sm:w-52"></div>
            <div class="absolute -right-20 bottom-1/4 h-44 w-44 rounded-full bg-[#0b1b57]/8 blur-3xl sm:h-52 sm:w-52"></div>

            <div class="relative z-10 grid min-h-screen grid-rows-[auto_1fr_auto] px-3 py-3 sm:px-4 sm:py-4">
                <header class="mx-auto w-full max-w-5xl rounded-[1.35rem] border border-[#091342] bg-[#0b1b57] px-5 py-3 text-center text-white shadow-[0_12px_28px_rgba(11,27,87,0.16)] sm:px-6 sm:py-4">
                    <h2 class="font-['Outfit'] text-xl font-bold tracking-[0.03em] sm:text-[1.7rem]">Hospital Privado Malacatán</h2>
                    <p class="mt-1 text-xs text-white/85 sm:text-sm">Nuestro compromiso es con la vida</p>
                </header>

                <main class="flex items-center justify-center py-4 sm:py-5">
                    {{ $slot }}
                </main>

                <footer class="mx-auto w-full max-w-3xl rounded-[1rem] border border-[#091342] bg-[#0b1b57] px-4 py-2 text-center text-xs font-medium text-white shadow-[0_8px_20px_rgba(11,27,87,0.12)] sm:px-5 sm:text-sm">
                    <p>Team mobux y dippsi</p>
                </footer>
            </div>
        </div>
    </body>
</html>
