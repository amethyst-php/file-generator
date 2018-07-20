<?php

namespace Railken\LaraOre\FileGenerator\Attributes\Description\Exceptions;

use Railken\LaraOre\FileGenerator\Exceptions\FileGeneratorAttributeException;

class FileGeneratorDescriptionNotAuthorizedException extends FileGeneratorAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'description';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FILEGENERATOR_DESCRIPTION_NOT_AUTHTORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
