<?php

namespace Railken\Amethyst\Events\FileGenerator;

use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Models\File;
use Railken\Amethyst\Models\FileGenerator;
use Railken\Lem\Contracts\AgentContract;

class FileGenerated
{
    use SerializesModels;

    public $generator;
    public $file;
    public $agent;

    /**
     * Create a new event instance.
     *
     * @param \Railken\Amethyst\Models\FileGenerator $generator
     * @param \Railken\Amethyst\Models\File          $file
     * @param \Railken\Lem\Contracts\AgentContract   $agent
     */
    public function __construct(FileGenerator $generator, File $file, AgentContract $agent = null)
    {
        $this->generator = $generator;
        $this->file = $file;
        $this->agent = $agent;
    }
}
