<?php

namespace Railken\Amethyst\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Railken\Amethyst\Api\Http\Controllers\RestManagerController;
use Railken\Amethyst\Api\Http\Controllers\Traits as RestTraits;
use Railken\Amethyst\Managers\DataBuilderManager;
use Railken\Amethyst\Managers\FileGeneratorManager;

class FileGeneratorsController extends RestManagerController
{
    use RestTraits\RestIndexTrait;
    use RestTraits\RestShowTrait;
    use RestTraits\RestCreateTrait;
    use RestTraits\RestUpdateTrait;
    use RestTraits\RestRemoveTrait;

    /**
     * The class of the manager.
     *
     * @var string
     */
    public $class = FileGeneratorManager::class;

    /**
     * Execute.
     *
     * @param int                      $id
     * @param \Illuminate\Http\Request $request
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function execute(int $id, Request $request)
    {
        /** @var \Railken\Amethyst\Managers\FileGeneratorManager */
        $manager = $this->manager;

        /** @var \Railken\Amethyst\Models\FileGenerator */
        $generator = $manager->getRepository()->findOneById($id);

        if ($generator == null) {
            return $this->not_found();
        }

        $result = $manager->generate($generator, (array) $request->input('data'));

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
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function render(Request $request)
    {
        /** @var \Railken\Amethyst\Managers\FileGeneratorManager */
        $manager = $this->manager;

        $dbm = new DataBuilderManager();

        /** @var \Railken\Amethyst\Models\DataBuilder */
        $data_builder = $dbm->getRepository()->findOneById(intval($request->input('data_builder_id')));

        if ($data_builder == null) {
            return $this->error([['message' => 'invalid data_builder_id']]);
        }

        $data = (array) $request->input('data', null);

        $result = $dbm->build($data_builder, $data);

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        $data = array_merge($data, $result->getResource());

        $result = $manager->render(
            $data_builder,
            strval($request->input('filetype')),
            [
                'body'     => strval($request->input('body')),
                'filename' => strval($request->input('filename')),
            ],
            $data
        );

        if (!$result->ok()) {
            return $this->error(['errors' => $result->getSimpleErrors()]);
        }

        $resource = $result->getResource();

        return $this->success(['resource' => [
            'body'     => base64_encode($resource['body']),
            'filename' => base64_encode($resource['filename']),
        ]]);
    }
}
