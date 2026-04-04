<?php

return [
    'roles' => [
        'administrador' => [
            'usuarios.manage',
            'laboratorio.view',
            'farmacia.view',
            'resumenes.view',
            'resumenes.export',
        ],
        'laboratorio' => [
            'laboratorio.view',
        ],
        'farmacia' => [
            'farmacia.view',
        ],
        'licenciado' => [
            'resumenes.view',
            'resumenes.export',
        ],
    ],
    'modules' => [
        [
            'ability' => 'usuarios.manage',
            'title' => 'Gestión de usuarios',
            'description' => 'Administra usuarios, cambia roles, actualiza datos y controla accesos al sistema.',
            'route' => 'usuarios.index',
            'button' => 'Ingresar al modulo',
            'badge' => 'Modulo administrativo',
        ],
        [
            'ability' => 'laboratorio.view',
            'title' => 'Módulo de laboratorio',
            'description' => 'Consulta y gestiona los procesos operativos del laboratorio segun el rol asignado.',
            'route' => 'laboratorio.index',
            'button' => 'Ingresar al modulo',
            'badge' => 'Modulo operativo',
        ],
        [
            'ability' => 'farmacia.view',
            'title' => 'Módulo de farmacia',
            'description' => 'Accede a las operaciones del area de farmacia con visibilidad controlada por rol.',
            'route' => 'farmacia.index',
            'button' => 'Ingresar al modulo',
            'badge' => 'Modulo operativo',
        ],
        [
            'ability' => 'resumenes.view',
            'title' => 'Resumenes generales',
            'description' => 'Consulta resumenes generales y exporta informacion.',
            'route' => 'resumenes.index',
            'button' => 'Ingresar al modulo',
            'badge' => 'Modulo de reportes',
        ],
    ],
];
