<?php

return [
    'app' => [
        'file-generator' => [
            'enabled'    => true,
            'controller' => Amethyst\Http\Controllers\FileGeneratorController::class,
            'router'     => [
                'prefix' => '/data/file-generator',
                'as'     => 'file-generator.',
            ],
        ],
    ],
];
