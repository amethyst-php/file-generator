<?php

namespace Railken\LaraOre\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\LaraOre\Events\FileGeneratorFailed;
use Railken\LaraOre\Events\FileGeneratorGenerated;
use Railken\LaraOre\File\FileManager;
use Railken\LaraOre\FileGenerator\FileGenerator;
use Railken\LaraOre\FileGenerator\FileGeneratorManager;
use Railken\LaraOre\Template\TemplateManager;
use Railken\Laravel\Manager\Contracts\AgentContract;

class GenerateFileGenerator implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $generator;
    protected $data;
    protected $agent;

    /**
     * Create a new job instance.
     *
     * @param FileGenerator                                    $generator
     * @param array                                            $data
     * @param \Railken\Laravel\Manager\Contracts\AgentContract $agent
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

        $filename = sys_get_temp_dir().'/'.$tm->renderRaw('text/plain', $generator->filename, $data);

        $file = fopen($filename, 'w');

        if (!$file) {
            throw new \Exception();
        }

        $result = $fgm->render($generator->data_builder, $generator->filetype, $generator->body, $data);

        if (!$result->ok()) {
            return event(new FileGeneratorFailed($generator, $result->getErrors()[0], $this->agent));
        }

        fclose($file);

        $result = $fm->create([]);
        $resource = $result->getResource();

        $resource
            ->addMedia($filename)
            ->addCustomHeaders([
                'ContentDisposition' => 'attachment; filename='.basename($filename).'',
                'ContentType'        => 'text/csv',
            ])
            ->toMediaCollection('file');

        event(new FileGeneratorGenerated($generator, $result->getResource(), $this->agent));
    }
}
