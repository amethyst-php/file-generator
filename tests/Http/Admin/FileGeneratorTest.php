<?php

namespace Railken\Amethyst\Tests\Http\Admin;

use Railken\Amethyst\Api\Support\Testing\TestableBaseTrait;
use Railken\Amethyst\Fakers\FileGeneratorFaker;
use Railken\Amethyst\Managers\FileGeneratorManager;
use Railken\Amethyst\Tests\BaseTest;

class FileGeneratorTest extends BaseTest
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
    protected $config = 'amethyst.file-generator.http.admin.file-generator';

    public function testGenerate()
    {
        $manager = new FileGeneratorManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters());
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->post($this->getResourceUrl().'/'.$resource->id.'/generate', ['data' => ['name' => $resource->name]]);
        $response->assertStatus(200);
    }

    public function testRender()
    {
        $manager = new FileGeneratorManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters());
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
