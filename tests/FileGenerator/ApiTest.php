<?php

namespace Railken\LaraOre\Tests\FileGenerator;

use Illuminate\Support\Facades\Config;
use Railken\LaraOre\FileGenerator\FileGeneratorFaker;
use Railken\LaraOre\FileGenerator\FileGeneratorManager;
use Railken\LaraOre\Support\Testing\ApiTestableTrait;

class ApiTest extends BaseTest
{
    use ApiTestableTrait;

    /**
     * Retrieve basic url.
     *
     * @return string
     */
    public function getBaseUrl()
    {
        return Config::get('ore.api.router.prefix').Config::get('ore.file-generator.http.admin.router.prefix');
    }

    /**
     * Test common requests.
     */
    public function testSuccessCommon()
    {
        $this->commonTest($this->getBaseUrl(), FileGeneratorFaker::make()->parameters());
    }

    public function testGenerate()
    {
        $manager = new FileGeneratorManager();

        $result = $manager->create(FileGeneratorFaker::make()->parameters()->set('repository.class_name', \Railken\LaraOre\Tests\FileGenerator\Repositories\FileGeneratorRepository::class));
        $this->assertEquals(1, $result->ok());
        $resource = $result->getResource();

        $response = $this->post($this->getBaseUrl().'/'.$resource->id.'/generate', ['data' => ['name' => $resource->name]]);
        $response->assertStatus(200);
    }
}
