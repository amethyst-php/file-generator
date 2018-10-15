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
            'table'       => 'amethyst_file_generators',
            'comment'     => 'File Generator',
            'model'       => Railken\Amethyst\Models\FileGenerator::class,
            'schema'      => Railken\Amethyst\Schemas\FileGeneratorSchema::class,
            'repository'  => Railken\Amethyst\Repositories\FileGeneratorRepository::class,
            'serializer'  => Railken\Amethyst\Serializers\FileGeneratorSerializer::class,
            'validator'   => Railken\Amethyst\Validators\FileGeneratorValidator::class,
            'authorizer'  => Railken\Amethyst\Authorizers\FileGeneratorAuthorizer::class,
            'faker'       => Railken\Amethyst\Fakers\FileGeneratorFaker::class,
            'manager'     => Railken\Amethyst\Managers\FileGeneratorManager::class,
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
                'enabled'     => true,
                'controller'  => Railken\Amethyst\Http\Controllers\Admin\FileGeneratorsController::class,
                'router'      => [
                    'as'        => 'file-generator.',
                    'prefix'    => '/file-generators',
                ],
            ],
        ],
    ],
];
