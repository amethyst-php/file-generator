<?php

namespace Railken\LaraOre\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Railken\LaraOre\Events\FileGeneratorFailed;
use Railken\LaraOre\Events\FileGeneratorGenerated;
use Railken\LaraOre\Exceptions\FormattingException;
use Railken\LaraOre\File\FileManager;
use Railken\LaraOre\FileGenerator\FileGenerator;
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

        $tm = new TemplateManager();

        $repository = $generator->repository;

        try {
            $query = $repository->newInstanceQuery($data);

            $filename = sys_get_temp_dir().'/'.$tm->renderRaw('text/plain', $generator->filename, $data);

            $file = fopen($filename, 'w');

            if (!$file) {
                throw new \Exception();
            }

            $resources = $query->get();
            fwrite($file, $tm->renderRaw($generator->filetype, $generator->body, array_merge($data, $repository->parse($resources))));
        } catch (FormattingException | \PDOException | \Railken\SQ\Exceptions\QuerySyntaxException $e) {
            return event(new FileGeneratorFailed($generator, $e, $this->agent));
        } catch (\Twig_Error $e) {
            $e = new \Exception($e->getRawMessage().' on line '.$e->getTemplateLine());

            return event(new FileGeneratorFailed($generator, $e, $this->agent));
        }

        $fm = new FileManager();
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
