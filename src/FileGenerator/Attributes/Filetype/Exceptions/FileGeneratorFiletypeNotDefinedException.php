<?php

namespace Railken\LaraOre\FileGenerator\Attributes\Filetype\Exceptions;

use Railken\LaraOre\FileGenerator\Exceptions\FileGeneratorAttributeException;

class FileGeneratorFiletypeNotDefinedException extends FileGeneratorAttributeException
{
    /**
     * The reason (attribute) for which this exception is thrown.
     *
     * @var string
     */
    protected $attribute = 'filetype';

    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FILEGENERATOR_FILETYPE_NOT_DEFINED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'The %s is required';
}
