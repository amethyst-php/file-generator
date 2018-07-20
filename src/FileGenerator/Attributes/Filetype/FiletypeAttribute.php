<?php

namespace Railken\LaraOre\FileGenerator\Attributes\Filetype;

use Illuminate\Support\Facades\Config;
use Railken\Laravel\Manager\Attributes\BaseAttribute;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Tokens;

class FiletypeAttribute extends BaseAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'filetype';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = true;

    /**
     * Is the attribute unique.
     *
     * @var bool
     */
    protected $unique = false;

    /**
     * List of all exceptions used in validation.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_DEFINED    => Exceptions\FileGeneratorFiletypeNotDefinedException::class,
        Tokens::NOT_VALID      => Exceptions\FileGeneratorFiletypeNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\FileGeneratorFiletypeNotAuthorizedException::class,
        Tokens::NOT_UNIQUE     => Exceptions\FileGeneratorFiletypeNotUniqueException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'filegenerator.attributes.filetype.fill',
        Tokens::PERMISSION_SHOW => 'filegenerator.attributes.filetype.show',
    ];

    /**
     * Is a value valid ?
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     * @param mixed                                             $value
     *
     * @return bool
     */
    public function valid(EntityContract $entity, $value)
    {
        return in_array($value, array_keys(Config::get('ore.template.generators')));
    }
}
