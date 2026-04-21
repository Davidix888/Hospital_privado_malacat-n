<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $title ?? 'Panel' }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:500,600,700" rel="stylesheet" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="min-h-screen bg-[#f3f6fb] font-sans text-[#0b1b57]">
        @php
            $notificationMessage = session('error');
            $notificationMessages = collect();
            $notificationTitle = null;
            $notificationClasses = null;
            $notificationButtonClasses = null;

            if (session('error') || $errors->any()) {
                $notificationMessages = $notificationMessages
                    ->when(session('error'), fn ($messages) => $messages->push(session('error')))
                    ->merge($errors->all())
                    ->filter(fn ($message) => filled($message))
                    ->unique()
                    ->values();

                $notificationMessage = session('error') ?: 'Revisa los datos del formulario antes de guardar.';
                $notificationTitle = session('error_title', 'No se pudo completar la acción');
                $notificationClasses = 'border-red-200 bg-red-50 text-red-700';
                $notificationButtonClasses = 'text-red-700/70 hover:text-red-700';
            }
        @endphp

        <div class="relative min-h-screen overflow-hidden">
            <div class="absolute left-0 top-0 h-32 w-32 rounded-br-[2.25rem] border-r border-b border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute right-0 top-0 h-32 w-32 rounded-bl-[2.25rem] border-l border-b border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute bottom-0 left-0 h-32 w-32 rounded-tr-[2.25rem] border-r border-t border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>
            <div class="absolute bottom-0 right-0 h-32 w-32 rounded-tl-[2.25rem] border-l border-t border-[#0b1b57]/20 bg-white/60 sm:h-40 sm:w-40"></div>

            <div class="relative z-10 flex min-h-screen flex-col">
                <header class="border-b border-[#0b1b57]/10 bg-[#0b1b57] text-white">
                    <div class="mx-auto flex max-w-7xl items-center justify-between px-5 py-4 sm:px-8">
                        <a
                            href="{{ route('dashboard') }}"
                            class="flex items-center gap-4 rounded-2xl transition hover:bg-white/10"
                        >
                            <img
                                src="{{ asset('imagenes/Logo_Hospital.jpeg') }}"
                                alt="Logo Hospital Privado Malacatan"
                                class="h-14 w-14 rounded-2xl bg-white object-contain p-1.5 shadow-sm"
                            >
                            <div>
                                <p class="font-['Outfit'] text-xl font-semibold tracking-wide">Hospital Privado Malacat&aacute;n</p>
                                <p class="text-sm text-white/75">M&oacute;dulo administrativo</p>
                            </div>
                        </a>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button
                                type="submit"
                                class="cursor-pointer rounded-xl border border-white/20 px-4 py-2 text-sm font-medium text-white transition hover:bg-white/10"
                            >
                                Cerrar sesi&oacute;n
                            </button>
                        </form>
                    </div>
                </header>

                <main class="mx-auto w-full max-w-7xl flex-1 px-5 pb-10 pt-16 sm:px-12 sm:pb-12 sm:pt-20">
                    @if ($notificationTitle && $notificationMessage)
                        <div
                            id="panel-notification"
                            class="fixed right-6 top-24 z-50 max-w-sm border px-5 py-4 text-sm shadow-xl {{ $notificationClasses }}"
                        >
                            <div class="flex items-start justify-between gap-4">
                                <div class="space-y-1">
                                    <p class="font-semibold">{{ $notificationTitle }}</p>
                                    <p>{{ $notificationMessage }}</p>
                                    @if ($notificationMessages->count() > 1)
                                        <ul class="space-y-1 pt-1 text-xs">
                                            @foreach ($notificationMessages->skip(1) as $message)
                                                <li>{{ $message }}</li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                                <button
                                    type="button"
                                    class="text-lg font-semibold leading-none transition {{ $notificationButtonClasses }}"
                                    onclick="document.getElementById('panel-notification')?.remove()"
                                >
                                    &times;
                                </button>
                            </div>
                        </div>
                        <script>
                            setTimeout(() => document.getElementById('panel-notification')?.remove(), 6000);
                        </script>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
