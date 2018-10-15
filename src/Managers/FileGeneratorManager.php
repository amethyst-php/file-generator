<?php

namespace Railken\Amethyst\Managers;

use Illuminate\Support\Collection;
use Railken\Amethyst\Common\ConfigurableManager;
use Railken\Amethyst\Exceptions;
use Railken\Amethyst\Jobs\FileGenerator\GenerateFile;
use Railken\Amethyst\Models\DataBuilder;
use Railken\Amethyst\Models\FileGenerator;
use Railken\Bag;
use Railken\Lem\Manager;
use Railken\Lem\Result;

class FileGeneratorManager extends Manager
{
    use ConfigurableManager;

    /**
     * @var string
     */
    protected $config = 'amethyst.file-generator.data.file-generator';

    /**
     * Request a file-generator.
     *
     * @param FileGenerator $generator
     * @param array         $data
     *
     * @return \Railken\Lem\Contracts\ResultContract
     */
    public function generate(FileGenerator $generator, array $data = [])
    {
        $result = (new DataBuilderManager())->validateRaw($generator->data_builder, $data);

        if (!$result->ok()) {
            return $result;
        }

        dispatch(new GenerateFile($generator, $data, $this->getAgent()));

        return $result;
    }

    /**
     * Render a file.
     *
     * @param DataBuilder $data_builder
     * @param string      $filetype
     * @param array       $parameters
     * @param array       $data
     *
     * @return \Railken\Lem\Contracts\ResultContract
     */
    public function render(DataBuilder $data_builder, string $filetype, array $parameters, array $data = [])
    {
        $parameters = $this->castParameters($parameters);

        $tm = new TemplateManager();

        $result = new Result();

        try {
            $bag = new Bag($parameters);

            $bag->set('body', $tm->renderRaw($filetype, strval($bag->get('body')), $data));
            $bag->set('filename', $tm->renderRaw('text/plain', strval($bag->get('filename')), $data));

            $result->setResources(new Collection([$bag->toArray()]));
        } catch (\Twig_Error $e) {
            $e = new Exceptions\FileGeneratorRenderException($e->getRawMessage().' on line '.$e->getTemplateLine());

            $result->addErrors(new Collection([$e]));
        }

        return $result;
    }
}
