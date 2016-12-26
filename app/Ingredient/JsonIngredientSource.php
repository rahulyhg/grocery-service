<?php

namespace App\Ingredient;

use stdClass;
use App\Traits\IsJsonSource;
use App\Ingredient\Contracts\IngredientSource;

class JsonIngredientSource implements IngredientSource
{
    use IsJsonSource;

    public function __construct()
    {
        $this->dataDir = __DIR__ . '/data/';
    }

    public function findAll() : array
    {
        $files = $this->getDataFiles();

        return array_map(function ($file) {
            return json_decode(
                file_get_contents($this->dataDir . $file)
            );
        }, $files);
    }

    public function findById(int $id) : ?stdClass
    {
    }
}