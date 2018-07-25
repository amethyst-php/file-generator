<?php

namespace Railken\LaraOre\FileGenerator;

use Illuminate\Support\Facades\Validator;
use Railken\Laravel\Manager\ModelValidator;
use Railken\Laravel\Manager\Result;

class FileGeneratorValidator extends ModelValidator
{
    /**
     * Validate input submitted.
     *
     * @param array $schema
     * @param array $data
     *
     * @return \Railken\Laravel\Manager\Contracts\ResultContract
     */
    public function input(array $schema, array $data)
    {
        $result = new Result();

        if (count($schema) !== 0) {
            $validator = Validator::make($data, $schema);

            $errors = collect();

            foreach ($validator->errors()->getMessages() as $key => $error) {
                $errors[] = new Exceptions\FileGeneratorInputException($key, $error[0], isset($data[$key]) ? $data[$key] : null);
            }

            $result->addErrors($errors);
        }

        return $result;
    }
}
