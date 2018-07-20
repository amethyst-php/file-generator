<?php

namespace Railken\LaraOre\FileGenerator;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Validator;
use Railken\LaraOre\Jobs\GenerateFileGenerator;
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
        Attributes\CreatedAt\CreatedAtAttribute::class,
        Attributes\UpdatedAt\UpdatedAtAttribute::class,
        Attributes\DeletedAt\DeletedAtAttribute::class,
        Attributes\Input\InputAttribute::class,
        Attributes\Filename\FilenameAttribute::class,
        Attributes\Body\BodyAttribute::class,
        Attributes\RepositoryId\RepositoryIdAttribute::class,
        Attributes\Description\DescriptionAttribute::class,
        Attributes\Filetype\FiletypeAttribute::class,
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
        $result = new Result();

        if (count((array) $generator->input) !== 0) {
            $validator = Validator::make($data, (array) $generator->input);

            $errors = collect();

            foreach ($validator->errors()->getMessages() as $key => $error) {
                $errors[] = new Exceptions\FileGeneratorInputException($key, $error[0], $data[$key]);
            }

            $result->addErrors($errors);
        }

        if (!$result->ok()) {
            return $result;
        }

        dispatch(new GenerateFileGenerator($generator, $data, $this->getAgent()));

        return $result;
    }
}
