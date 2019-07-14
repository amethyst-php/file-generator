<?php

namespace Amethyst\Managers;

use Amethyst\Common\ConfigurableManager;
use Amethyst\Exceptions;
use Amethyst\Jobs\FileGenerator\GenerateFile;
use Amethyst\Models\DataBuilder;
use Amethyst\Models\FileGenerator;
use Illuminate\Support\Collection;
use Railken\Bag;
use Railken\Lem\Manager;
use Railken\Lem\Result;

/**
 * @method \Amethyst\Models\FileGenerator                 newEntity()
 * @method \Amethyst\Schemas\FileGeneratorSchema          getSchema()
 * @method \Amethyst\Repositories\FileGeneratorRepository getRepository()
 * @method \Amethyst\Serializers\FileGeneratorSerializer  getSerializer()
 * @method \Amethyst\Validators\FileGeneratorValidator    getValidator()
 * @method \Amethyst\Authorizers\FileGeneratorAuthorizer  getAuthorizer()
 */
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
    public function execute(FileGenerator $generator, array $data = [])
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

    /**
     * Describe extra actions.
     *
     * @return array
     */
    public function getDescriptor()
    {
        return [
            'components' => [
                'renderer',
            ],
            'actions' => [
                'executor',
            ],
        ];
    }
}
