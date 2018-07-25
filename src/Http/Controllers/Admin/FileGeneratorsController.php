<?php

namespace Railken\LaraOre\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Railken\LaraOre\Api\Http\Controllers\RestConfigurableController;
use Railken\LaraOre\Api\Http\Controllers\Traits as RestTraits;
use Railken\LaraOre\DataBuilder\DataBuilderManager;

class FileGeneratorsController extends RestConfigurableController
{
    use RestTraits\RestIndexTrait;
    use RestTraits\RestShowTrait;
    use RestTraits\RestCreateTrait;
    use RestTraits\RestUpdateTrait;
    use RestTraits\RestRemoveTrait;

    /**
     * The config path.
     *
     * @var string
     */
    public $config = 'ore.file-generator';

    /**
     * The attributes that are queryable.
     *
     * @var array
     */
    public $queryable = [
        'id',
        'name',
        'description',
        'data_builder_id',
        'filename',
        'filetype',
        'body',
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that are fillable.
     *
     * @var array
     */
    public $fillable = [
        'name',
        'description',
        'data_builder',
        'data_builder_id',
        'filename',
        'filetype',
        'body',
    ];

    /**
     * Generate.
     *
     * @param int                      $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function generate(int $id, Request $request)
    {
        /** @var \Railken\LaraOre\FileGenerator\FileGeneratorManager */
        $manager = $this->manager;

        /** @var \Railken\LaraOre\FileGenerator\FileGenerator */
        $report = $manager->getRepository()->findOneById($id);

        if ($report == null) {
            return $this->not_found();
        }

        $result = $manager->generate($report, (array) $request->input('data'));

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        return $this->success([]);
    }

    /**
     * Render raw template.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function render(Request $request)
    {
        /** @var \Railken\LaraOre\FileGenerator\FileGeneratorManager */
        $manager = $this->manager;

        /** @var \Railken\LaraOre\DataBuilder\DataBuilder */
        $data_builder = (new DataBuilderManager())->getRepository()->findOneById(intval($request->input('data_builder_id')));

        if ($data_builder == null) {
            return $this->error([['message' => 'invalid data_builder_id']]);
        }

        $result = $manager->render(
            $data_builder,
            strval($request->input('filetype')),
            strval($request->input('body')),
            (array) $request->input('data')
        );

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        return $this->success(['resource' => base64_encode($result->getResource())]);
    }
}
