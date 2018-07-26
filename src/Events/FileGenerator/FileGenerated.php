<?php

namespace Railken\LaraOre\Events\FileGenerator;

use Illuminate\Queue\SerializesModels;
use Railken\LaraOre\File\File;
use Railken\LaraOre\FileGenerator\FileGenerator;
use Railken\Laravel\Manager\Contracts\AgentContract;

class FileGenerated
{
    use SerializesModels;

    public $generator;
    public $file;
    public $agent;

    /**
     * Create a new event instance.
     *
     * @param \Railken\LaraOre\FileGenerator\FileGenerator     $generator
     * @param \Railken\LaraOre\File\File                       $file
     * @param \Railken\Laravel\Manager\Contracts\AgentContract $agent
     */
    public function __construct(FileGenerator $generator, File $file, AgentContract $agent = null)
    {
        $this->generator = $generator;
        $this->file = $file;
        $this->agent = $agent;
    }
}
