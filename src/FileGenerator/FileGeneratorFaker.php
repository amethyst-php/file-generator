<?php

namespace Railken\LaraOre\FileGenerator;

use Faker\Factory;
use Railken\Bag;
use Railken\LaraOre\DataBuilder\DataBuilderFaker;
use Railken\Laravel\Manager\BaseFaker;

class FileGeneratorFaker extends BaseFaker
{
    /**
     * @var string
     */
    protected $manager = FileGeneratorManager::class;

    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $faker = Factory::create();

        $bag = new Bag();
        $bag->set('name', $faker->name);
        $bag->set('description', $faker->text);
        $bag->set('data_builder', DataBuilderFaker::make()->parameters()->toArray());
        $bag->set('filename', 'users-{{ "now"|date("Ymd") }}');
        $bag->set('filetype', 'text/plain');
        $bag->set('body', 'test');

        return $bag;
    }
}
