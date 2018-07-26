<?php

namespace Railken\LaraOre\Events\FileGenerator;

use Exception;
use Illuminate\Queue\SerializesModels;
use Railken\LaraOre\FileGenerator\FileGenerator;
use Railken\Laravel\Manager\Contracts\AgentContract;

class FileFailed
{
    use SerializesModels;

    public $generator;
    public $error;
    public $agent;

    /**
     * Create a new event instance.
     *
     * @param \Railken\LaraOre\FileGenerator\FileGenerator     $generator
     * @param \Exception                                       $exception
     * @param \Railken\Laravel\Manager\Contracts\AgentContract $agent
     */
    public function __construct(FileGenerator $generator, Exception $exception, AgentContract $agent = null)
    {
        $this->generator = $generator;
        $this->error = (object) [
            'class'   => get_class($exception),
            'message' => $exception->getMessage(),
        ];

        $this->agent = $agent;
    }
}
