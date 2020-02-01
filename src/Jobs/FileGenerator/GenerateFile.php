<?php

namespace Amethyst\Jobs\FileGenerator;

use Amethyst\Events\FileGenerator\FileFailed;
use Amethyst\Events\FileGenerator\FileGenerated;
use Amethyst\Managers\DataBuilderManager;
use Amethyst\Managers\FileGeneratorManager;
use Amethyst\Managers\FileManager;
use Amethyst\Managers\TemplateManager;
use Amethyst\Models\FileGenerator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Config;
use Railken\Bag;
use Railken\Lem\Contracts\AgentContract;

class GenerateFile implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

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
        $result = $this->generate();
        $generator = $this->generator;

        if (!$result->ok()) {
            event(new FileFailed($generator, $result->getErrors()[0], $this->agent));
        } else {
            event(new FileGenerated($generator, $result->getResource(), $this->agent));
        }

        return $result;
    }

    /**
     * Generate a file.
     *
     * @return \Railken\Lem\Result
     */
    public function generate()
    {
        $generator = $this->generator;
        $data = $this->data;

        $fgm = new FileGeneratorManager();
        $fm = new FileManager();
        $tm = new TemplateManager();
        $dbm = new DataBuilderManager();

        $result = $dbm->build($generator->data_builder, $data);

        if (!$result->ok()) {
            return $result;
        }

        // Overwrite filename if driver is local
        $diskName = Config::get('medialibrary.disk_name');

        if (Config::get("filesystems.disks.$diskName.driver", 'local') === 'local') {
            $generator->filename = bin2hex(random_bytes(32)).'-'.$generator->filename;
        }

        $data = $result->getResource();
        $result = $fgm->render($generator->data_builder, $generator->filetype, [
            'body'     => $generator->body,
            'filename' => sys_get_temp_dir().'/'.$generator->filename,
        ], $data);

        if (!$result->ok()) {
            return $result;
        }

        $bag = new Bag($result->getResource());

        file_put_contents($bag->get('filename'), $bag->get('body'));

        $result = $fm->create(['name' => basename($bag->get('filename'))]);
        $resource = $result->getResource();

        $resource
            ->addMedia($bag->get('filename'))
            ->addCustomHeaders([
                'ContentDisposition' => 'attachment; filename='.basename($bag->get('filename')).'',
            ])
            ->toMediaCollection('file');

        return $result;
    }
}
