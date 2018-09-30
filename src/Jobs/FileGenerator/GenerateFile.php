<?php

namespace Railken\Amethyst\Jobs\FileGenerator;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\Amethyst\Events\FileGenerator\FileFailed;
use Railken\Amethyst\Events\FileGenerator\FileGenerated;
use Railken\Amethyst\Managers\DataBuilderManager;
use Railken\Amethyst\Managers\FileGeneratorManager;
use Railken\Amethyst\Managers\FileManager;
use Railken\Amethyst\Managers\TemplateManager;
use Railken\Amethyst\Models\FileGenerator;
use Railken\Bag;
use Railken\Lem\Contracts\AgentContract;

class GenerateFile implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $generator;
    protected $data;
    protected $agent;

    /**
     * Create a new job instance.
     *
     * @param FileGenerator                        $generator
     * @param array                                $data
     * @param \Railken\Lem\Contracts\AgentContract $agent
     */
    public function __construct(FileGenerator $generator, array $data = [], AgentContract $agent = null)
    {
        $this->generator = $generator;
        $this->data = $data;
        $this->agent = $agent;
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        $generator = $this->generator;
        $data = $this->data;

        $fgm = new FileGeneratorManager();
        $fm = new FileManager();
        $tm = new TemplateManager();
        $dbm = new DataBuilderManager();

        $result = $dbm->build($generator->data_builder, $data);

        if (!$result->ok()) {
            return event(new FileFailed($generator, $result->getErrors()[0], $this->agent));
        }

        $data = $result->getResource();
        $result = $fgm->render($generator->data_builder, $generator->filetype, [
            'body'         => $generator->body,
            'filename'     => sys_get_temp_dir().'/'.$generator->filename,
        ], $data);

        if (!$result->ok()) {
            return event(new FileFailed($generator, $result->getErrors()[0], $this->agent));
        }

        $bag = new Bag($result->getResource());

        file_put_contents($bag->get('filename'), $bag->get('body'));

        $result = $fm->create([]);
        $resource = $result->getResource();

        $resource
            ->addMedia($bag->get('filename'))
            ->addCustomHeaders([
                'ContentDisposition' => 'attachment; filename='.basename($bag->get('filename')).'',
                'ContentType'        => 'text/csv',
            ])
            ->toMediaCollection('file');

        event(new FileGenerated($generator, $result->getResource(), $this->agent));
    }
}
