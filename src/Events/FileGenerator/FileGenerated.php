<?php

namespace Amethyst\Events\FileGenerator;

use Illuminate\Queue\SerializesModels;
use Amethyst\Models\File;
use Amethyst\Models\FileGenerator;
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
     * @param \Amethyst\Models\FileGenerator $generator
     * @param \Amethyst\Models\File          $file
     * @param \Railken\Lem\Contracts\AgentContract   $agent
     */
    public function __construct(FileGenerator $generator, File $file, AgentContract $agent = null)
    {
        $this->generator = $generator;
        $this->file = $file;
        $this->agent = $agent;
    }
}
