<?php

return [
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
];
