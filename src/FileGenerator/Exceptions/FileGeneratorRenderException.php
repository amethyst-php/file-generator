<?php

namespace Railken\LaraOre\FileGenerator\Exceptions;

class FileGeneratorRenderException extends FileGeneratorException
{
    /**
     * The code to identify the error.
     *
     * @var string
     */
    protected $code = 'FILE-GENERATOR_RENDER_ERROR';

    /**
     * Construct.
     *
     * @param mixed $message
     */
    public function __construct($message = null)
    {
        $this->message = $message;

        parent::__construct();
    }
}
