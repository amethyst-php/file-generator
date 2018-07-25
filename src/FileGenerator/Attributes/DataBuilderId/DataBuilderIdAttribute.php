<?php

namespace Railken\LaraOre\FileGenerator\Attributes\DataBuilderId;

use Railken\Laravel\Manager\Attributes\BelongsToAttribute;
use Railken\Laravel\Manager\Contracts\EntityContract;
use Railken\Laravel\Manager\Tokens;

class DataBuilderIdAttribute extends BelongsToAttribute
{
    /**
     * Name attribute.
     *
     * @var string
     */
    protected $name = 'data_builder_id';

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
        Tokens::NOT_DEFINED    => Exceptions\FileGeneratorDataBuilderIdNotDefinedException::class,
        Tokens::NOT_VALID      => Exceptions\FileGeneratorDataBuilderIdNotValidException::class,
        Tokens::NOT_AUTHORIZED => Exceptions\FileGeneratorDataBuilderIdNotAuthorizedException::class,
        Tokens::NOT_UNIQUE     => Exceptions\FileGeneratorDataBuilderIdNotUniqueException::class,
    ];

    /**
     * List of all permissions.
     */
    protected $permissions = [
        Tokens::PERMISSION_FILL => 'filegenerator.attributes.data_builder_id.fill',
        Tokens::PERMISSION_SHOW => 'filegenerator.attributes.data_builder_id.show',
    ];

    /**
     * Retrieve the name of the relation.
     *
     * @return string
     */
    public function getRelationName()
    {
        return 'data_builder';
    }

    /**
     * Retrieve eloquent relation.
     *
     * @param \Railken\LaraOre\FileGenerator\FileGenerator $entity
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function getRelationBuilder(EntityContract $entity)
    {
        return $entity->data_builder();
    }

    /**
     * Retrieve relation manager.
     *
     * @param \Railken\LaraOre\FileGenerator\FileGenerator $entity
     *
     * @return \Railken\Laravel\Manager\Contracts\ManagerContract
     */
    public function getRelationManager(EntityContract $entity)
    {
        return new \Railken\LaraOre\DataBuilder\DataBuilderManager($this->getManager()->getAgent());
    }
}
