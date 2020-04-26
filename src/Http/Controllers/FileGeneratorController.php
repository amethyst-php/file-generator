<?php

namespace Amethyst\Http\Controllers;

use Amethyst\Core\Http\Controllers\RestManagerController;
use Amethyst\Managers\DataBuilderManager;
use Illuminate\Http\Request;

class FileGeneratorController extends RestManagerController
{
    public function __construct()
    {
        $this->manager = app('amethyst')->get('file-generator');
    }

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
        /** @var \Amethyst\Managers\FileGeneratorManager */
        $manager = $this->manager;

        /** @var \Amethyst\Models\FileGenerator */
        $generator = $manager->getRepository()->findOneById($id);

        if ($generator == null) {
            abort(404);
        }

        $result = $manager->execute($generator, (array) $request->input('data'));

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
        /** @var \Amethyst\Managers\FileGeneratorManager */
        $manager = $this->manager;

        $dbm = new DataBuilderManager();

        /** @var \Amethyst\Models\DataBuilder */
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
