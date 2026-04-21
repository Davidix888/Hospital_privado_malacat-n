@props(['current' => ''])

@php
    $links = [
        [
            'key' => 'farmacia',
            'label' => 'Men&uacute; farmacia',
            'route' => route('farmacia.index'),
        ],
        [
            'key' => 'purchases.index',
            'label' => 'Compras registradas',
            'route' => route('farmacia.purchases.index'),
        ],
        [
            'key' => 'purchases.create',
            'label' => 'Registrar compra',
            'route' => route('farmacia.purchases.create'),
        ],
        [
            'key' => 'medicines.index',
            'label' => 'Cat&aacute;logo',
            'route' => route('farmacia.medicines.index'),
        ],
        [
            'key' => 'suppliers.index',
            'label' => 'Proveedores',
            'route' => route('farmacia.suppliers.index'),
        ],
    ];
@endphp

<div class="mt-6 border border-[#0b1b57]/10 bg-[#f8faff] p-4">
    <div class="flex flex-wrap gap-3">
        @foreach ($links as $link)
            <a
                href="{{ $link['route'] }}"
                @class([
                    'inline-flex items-center justify-center whitespace-nowrap rounded-[1.1rem] px-4 py-2 text-sm font-semibold transition',
                    'bg-[#0b1b57] text-white hover:bg-[#10256f]' => $current === $link['key'],
                    'border border-[#0b1b57]/20 text-[#0b1b57] hover:bg-[#0b1b57]/5' => $current !== $link['key'],
                ])
            >
                {!! $link['label'] !!}
            </a>
        @endforeach
    </div>
</div>
