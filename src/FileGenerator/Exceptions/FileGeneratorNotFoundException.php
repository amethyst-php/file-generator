<?php

namespace Railken\LaraOre\FileGenerator\Exceptions;

class FileGeneratorNotFoundException extends FileGeneratorException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FILEGENERATOR_NOT_FOUND';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = 'Not found';
}
