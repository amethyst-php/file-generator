<?php

namespace Railken\LaraOre\Tests\FileGenerator;

use Railken\LaraOre\DataBuilder\DataBuilderManager;
use Railken\LaraOre\FileGenerator\FileGeneratorFaker;
use Railken\LaraOre\FileGenerator\FileGeneratorManager;
use Railken\LaraOre\Support\Testing\ManagerTestableTrait;

class ManagerTest extends BaseTest
{
    use ManagerTestableTrait;

    /**
     * Retrieve basic url.
     *
     * @return \Railken\Laravel\Manager\Contracts\ManagerContract
     */
    public function getManager()
    {
        return new FileGeneratorManager();
    }

    public function testSuccessCommon()
    {
        $this->commonTest($this->getManager(), FileGeneratorFaker::make()->parameters());
    }

    public function testGenerate()
    {
        $manager = $this->getManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('data_builder.repository.class_name', \Railken\LaraOre\Tests\FileGenerator\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());
    }

    public function testRender()
    {
        $manager = $this->getManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('data_builder.repository.class_name', \Railken\LaraOre\Tests\FileGenerator\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();
        $result = $manager->render($resource->data_builder, 'text/html', [
            'body' => '{{ name }}',
        ], (new DataBuilderManager())->build($resource->data_builder, ['name' => 'ban'])->getResource());

        $this->assertEquals(true, $result->ok());
    }
}
