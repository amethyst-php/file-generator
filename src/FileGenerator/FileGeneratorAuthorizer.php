<?php

namespace Railken\LaraOre\FileGenerator;

use Railken\Laravel\Manager\ModelAuthorizer;
use Railken\Laravel\Manager\Tokens;

class FileGeneratorAuthorizer extends ModelAuthorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'file_generator.create',
        Tokens::PERMISSION_UPDATE => 'file_generator.update',
        Tokens::PERMISSION_SHOW   => 'file_generator.show',
        Tokens::PERMISSION_REMOVE => 'file_generator.remove',
    ];
}
