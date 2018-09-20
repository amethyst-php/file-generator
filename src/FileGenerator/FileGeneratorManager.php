<?php

namespace Railken\LaraOre\FileGenerator;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Config;
use Railken\Bag;
use Railken\LaraOre\DataBuilder\DataBuilder;
use Railken\LaraOre\DataBuilder\DataBuilderManager;
use Railken\LaraOre\Jobs\FileGenerator\GenerateFile;
use Railken\LaraOre\Template\TemplateManager;
use Railken\Laravel\Manager\Contracts\AgentContract;
use Railken\Laravel\Manager\ModelManager;
use Railken\Laravel\Manager\Result;
use Railken\Laravel\Manager\Tokens;

class FileGeneratorManager extends ModelManager
{
    /**
     * Class name entity.
     *
     * @var string
     */
    public $entity = FileGenerator::class;

    /**
     * List of all attributes.
     *
     * @var array
     */
    protected $attributes = [
        Attributes\Id\IdAttribute::class,
        Attributes\Name\NameAttribute::class,
        Attributes\Description\DescriptionAttribute::class,
        Attributes\Filename\FilenameAttribute::class,
        Attributes\Body\BodyAttribute::class,
        Attributes\Filetype\FiletypeAttribute::class,
        Attributes\DataBuilderId\DataBuilderIdAttribute::class,
        Attributes\CreatedAt\CreatedAtAttribute::class,
        Attributes\UpdatedAt\UpdatedAtAttribute::class,
        Attributes\DeletedAt\DeletedAtAttribute::class,
    ];

    /**
     * List of all exceptions.
     *
     * @var array
     */
    protected $exceptions = [
        Tokens::NOT_AUTHORIZED => Exceptions\FileGeneratorNotAuthorizedException::class,
    ];

    /**
     * Construct.
     *
     * @param AgentContract $agent
     */
    public function __construct(AgentContract $agent = null)
    {
        $this->entity = Config::get('ore.file-generator.entity');
        $this->attributes = array_merge($this->attributes, array_values(Config::get('ore.file-generator.attributes')));

        $classRepository = Config::get('ore.file-generator.repository');
        $this->setRepository(new $classRepository($this));

        $classSerializer = Config::get('ore.file-generator.serializer');
        $this->setSerializer(new $classSerializer($this));

        $classAuthorizer = Config::get('ore.file-generator.authorizer');
        $this->setAuthorizer(new $classAuthorizer($this));

        $classValidator = Config::get('ore.file-generator.validator');
        $this->setValidator(new $classValidator($this));

        parent::__construct($agent);
    }

    /**
     * Request a file-generator.
     *
     * @param FileGenerator $generator
     * @param array         $data
     *
     * @return \Railken\Laravel\Manager\Contracts\ResultContract
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
     * @return \Railken\Laravel\Manager\Contracts\ResultContract
     */
    public function render(DataBuilder $data_builder, string $filetype, array $parameters, array $data = [])
    {
        $parameters = $this->castParameters($parameters);

        $tm = new TemplateManager();

        $result = new Result();

        try {
            $bag = new Bag($parameters);

            $bag->set('body', $tm->renderRaw($filetype, strval($bag->get('body')), $data));
            $bag->set('filename', $tm->renderRaw($filetype, strval($bag->get('filename')), $data));

            $result->setResources(new Collection([$bag->toArray()]));
        } catch (\Twig_Error $e) {
            $e = new Exceptions\FileGeneratorRenderException($e->getRawMessage().' on line '.$e->getTemplateLine());

            $result->addErrors(new Collection([$e]));
        }

        return $result;
    }
}
