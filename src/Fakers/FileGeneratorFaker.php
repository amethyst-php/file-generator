<?php

namespace Railken\Amethyst\Fakers;

use Faker\Factory;
use Railken\Amethyst\DataBuilders\FileGeneratorDataBuilder;
use Railken\Bag;
use Railken\Lem\Faker;

class FileGeneratorFaker extends Faker
{
    /**
     * @return \Railken\Bag
     */
    public function parameters()
    {
        $faker = Factory::create();

        $bag = new Bag();
        $bag->set('name', $faker->name);
        $bag->set('description', $faker->text);
        $bag->set('data_builder', DataBuilderFaker::make()->parameters()->set('data_builder.class_name', FileGeneratorDataBuilder::class)->toArray());
        $bag->set('filename', 'users-{{ "now"|date("Ymd") }}');
        $bag->set('filetype', 'text/plain');
        $bag->set('body', 'test');

        return $bag;
    }
}
