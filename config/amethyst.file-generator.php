<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Data
    |--------------------------------------------------------------------------
    |
    | Here you can change the table name and the class components.
    |
    */
    'data' => [
        'file-generator' => [
            'table'      => 'amethyst_file_generators',
            'comment'    => 'File Generator',
            'model'      => Amethyst\Models\FileGenerator::class,
            'schema'     => Amethyst\Schemas\FileGeneratorSchema::class,
            'repository' => Amethyst\Repositories\FileGeneratorRepository::class,
            'serializer' => Amethyst\Serializers\FileGeneratorSerializer::class,
            'validator'  => Amethyst\Validators\FileGeneratorValidator::class,
            'authorizer' => Amethyst\Authorizers\FileGeneratorAuthorizer::class,
            'faker'      => Amethyst\Fakers\FileGeneratorFaker::class,
            'manager'    => Amethyst\Managers\FileGeneratorManager::class,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Http configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the routes
    |
    */
    'http' => [
        'admin' => [
            'file-generator' => [
                'enabled'    => true,
                'controller' => Amethyst\Http\Controllers\Admin\FileGeneratorsController::class,
                'router'     => [
                    'as'     => 'file-generator.',
                    'prefix' => '/file-generators',
                ],
            ],
        ],
    ],
];
