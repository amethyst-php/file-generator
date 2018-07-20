<?php

namespace Railken\LaraOre\FileGenerator\Attributes\Description\Exceptions;

use Railken\LaraOre\FileGenerator\Exceptions\FileGeneratorAttributeException;

class FileGeneratorDescriptionNotValidException extends FileGeneratorAttributeException
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
    protected $code = 'FILEGENERATOR_DESCRIPTION_NOT_VALID';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is not valid';
}
