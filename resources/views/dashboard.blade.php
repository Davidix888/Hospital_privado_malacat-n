<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Panel | {{ config('app.name', 'Hospital Privado Malacatan') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700|outfit:500,600,700" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif
    </head>
    <body class="min-h-screen bg-slate-950 text-slate-100">
        <main class="mx-auto flex min-h-screen max-w-5xl items-center px-6 py-12">
            <section class="w-full rounded-[2rem] border border-white/10 bg-slate-900/80 p-8 shadow-2xl shadow-slate-950/60">
                <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                    <div class="space-y-2">
                        <p class="text-sm uppercase tracking-[0.3em] text-emerald-300">Panel de control</p>
                        <h1 class="font-['Outfit'] text-4xl font-semibold text-white">
                            Bienvenido, {{ auth()->user()->name }}
                        </h1>
                        <p class="text-slate-300">
                            El acceso ya esta listo. Desde aqui podemos construir las siguientes vistas del sistema.
                        </p>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button
                            type="submit"
                            class="inline-flex items-center justify-center rounded-2xl border border-white/10 px-5 py-3 font-medium text-slate-200 transition hover:border-rose-300 hover:text-rose-200"
                        >
                            Cerrar sesion
                        </button>
                    </form>
                </div>
            </section>
        </main>
    </body>
</html>
