<?php

namespace Amethyst\Events\FileGenerator;

use Amethyst\Models\File;
use Amethyst\Models\FileGenerator;
use Illuminate\Queue\SerializesModels;
use Railken\Lem\Contracts\AgentContract;

class FileGenerated
{
    use SerializesModels;

    public $generator;
    public $file;
    public $agent;
    public $data;

    /**
     * Create a new event instance.
     *
     * @param \Amethyst\Models\FileGenerator       $generator
     * @param \Amethyst\Models\File                $file
     * @param \Railken\Lem\Contracts\AgentContract $agent
     * @param $data
     */
    public function __construct(FileGenerator $generator, File $file, AgentContract $agent = null, $data)
    {
        $this->generator = $generator;
        $this->file = $file;
        $this->agent = $agent;
        $this->data = $data;
    }
}
