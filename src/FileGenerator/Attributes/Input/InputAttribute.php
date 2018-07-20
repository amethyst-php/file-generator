<?php

namespace Railken\LaraOre\FileGenerator\Attributes\Input;

use Railken\Laravel\Manager\Attributes\BaseAttribute;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Tokens;

class InputAttribute extends BaseAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'input';

    /**
     * Is the attribute required
     * This will throw not_defined exception for non defined value and non existent model.
     *
     * @var bool
     */
    protected $required = false;

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
        Tokens::NOT_DEFINED    => Exceptions\FileGeneratorInputNotDefinedException::class,
        Tokens::NOT_VALID      => Exceptions\FileGeneratorInputNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\FileGeneratorInputNotAuthorizedException::class,
        Tokens::NOT_UNIQUE     => Exceptions\FileGeneratorInputNotUniqueException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'filegenerator.attributes.input.fill',
        Tokens::PERMISSION_SHOW => 'filegenerator.attributes.input.show',
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
        return true;
    }

    /**
     * Retrieve default value.
     *
     * @param \Railken\Laravel\Manager\Contracts\EntityContract $entity
     *
     * @return mixed
     */
    public function getDefault(EntityContract $entity)
    {
        return (object) [];
    }
}
