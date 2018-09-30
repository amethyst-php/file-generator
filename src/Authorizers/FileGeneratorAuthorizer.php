<?php

namespace Railken\Amethyst\Authorizers;

use Railken\Lem\Authorizer;
use Railken\Lem\Tokens;

class FileGeneratorAuthorizer extends Authorizer
{
    /**
     * List of all permissions.
     *
     * @var array
     */
    protected $permissions = [
        Tokens::PERMISSION_CREATE => 'file-generator.create',
        Tokens::PERMISSION_UPDATE => 'file-generator.update',
        Tokens::PERMISSION_SHOW   => 'file-generator.show',
        Tokens::PERMISSION_REMOVE => 'file-generator.remove',
    ];
}
