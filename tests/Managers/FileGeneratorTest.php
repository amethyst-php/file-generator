<?php

namespace Railken\Amethyst\Tests\Managers;

use Railken\Amethyst\Fakers\FileGeneratorFaker;
use Railken\Amethyst\Managers\DataBuilderManager;
use Railken\Amethyst\Managers\FileGeneratorManager;
use Railken\Amethyst\Tests\BaseTest;
use Railken\Lem\Support\Testing\TestableBaseTrait;

class FileGeneratorTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Manager class.
     *
     * @var string
     */
    protected $manager = FileGeneratorManager::class;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = FileGeneratorFaker::class;

    public function testGenerate()
    {
        $manager = $this->getManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('data_builder.repository.class_name', \Railken\Amethyst\Tests\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();
        $result = $manager->generate($resource, ['name' => $resource->name]);
        $this->assertEquals(true, $result->ok());
    }

    public function testRender()
    {
        $manager = $this->getManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('data_builder.repository.class_name', \Railken\Amethyst\Tests\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();
        $result = $manager->render($resource->data_builder, 'text/html', [
            'body' => '{{ name }}',
        ], (new DataBuilderManager())->build($resource->data_builder, ['name' => 'ban'])->getResource());

        $this->assertEquals(true, $result->ok());
    }
}
