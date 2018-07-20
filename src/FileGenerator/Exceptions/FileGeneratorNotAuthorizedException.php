<?php

namespace Railken\LaraOre\FileGenerator\Exceptions;

class FileGeneratorNotAuthorizedException extends FileGeneratorException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FILEGENERATOR_NOT_AUTHORIZED';

    /**
     * The message.
     *
     * @var string
     */
    protected $message = "You're not authorized to interact with %s, missing %s permission";
}
