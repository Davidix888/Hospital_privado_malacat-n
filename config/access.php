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
            'button' => 'Ingresar al módulo',
            'badge' => 'Módulo administrativo',
        ],
        [
            'ability' => 'laboratorio.view',
            'title' => 'Módulo de laboratorio',
            'description' => 'Consulta y gestiona los procesos operativos del laboratorio según el rol asignado.',
            'route' => 'laboratorio.index',
            'button' => 'Ingresar al módulo',
            'badge' => 'Módulo operativo',
        ],
        [
            'ability' => 'farmacia.view',
            'title' => 'Módulo de farmacia',
            'description' => 'Accede a las operaciones del área de farmacia con visibilidad controlada por rol.',
            'route' => 'farmacia.index',
            'button' => 'Ingresar al módulo',
            'badge' => 'Módulo operativo',
        ],
        [
            'ability' => 'resumenes.view',
            'title' => 'Resúmenes generales',
            'description' => 'Consulta resúmenes generales y exporta información.',
            'route' => 'resumenes.index',
            'button' => 'Ingresar al módulo',
            'badge' => 'Módulo de reportes',
        ],
    ],
];
