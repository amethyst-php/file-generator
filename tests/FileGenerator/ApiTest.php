<?php

namespace Railken\LaraOre\Tests\FileGenerator;

use Illuminate\Support\Facades\Config;
use Railken\LaraOre\Api\Support\Testing\TestableBaseTrait;
use Railken\LaraOre\FileGenerator\FileGeneratorFaker;
use Railken\LaraOre\FileGenerator\FileGeneratorManager;

class ApiTest extends BaseTest
{
    use TestableBaseTrait;

    /**
     * Faker class.
     *
     * @var string
     */
    protected $faker = FileGeneratorFaker::class;

    /**
     * Router group resource.
     *
     * @var string
     */
    protected $group = 'admin';

    /**
     * Base path config.
     *
     * @var string
     */
    protected $config = 'ore.file-generator';

    public function testGenerate()
    {
        $manager = new FileGeneratorManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('data_builder.repository.class_name', \Railken\LaraOre\Tests\FileGenerator\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->post($this->getResourceUrl().'/'.$resource->id.'/generate', ['data' => ['name' => $resource->name]]);
        $response->assertStatus(200);
    }

    public function testRender()
    {
        $manager = new FileGeneratorManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('data_builder.repository.class_name', \Railken\LaraOre\Tests\FileGenerator\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());

        $resource = $result->getResource();

        $response = $this->post($this->getResourceUrl().'/render', [
            'data_builder_id' => $resource->data_builder->id,
            'filetype'        => 'text/html',
            'body'            => '{{ name }}',
            'data'            => ['name' => 'ban'],
        ]);
        $response->assertStatus(200);
    }
}
