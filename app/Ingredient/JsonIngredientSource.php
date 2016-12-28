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

    public function findById(int $ingredientId) : ?stdClass
    {
        $file = $this->dataDir . 'ingredient-' . $ingredientId . '.json';

        if (!file_exists($file)) {
            return null;
        }

        return json_decode(
            file_get_contents($file)
        );
    }

    public function persistIngredient(
        Ingredient $ingredientToCreate
    ) : int {
        $ingredient = clone $ingredientToCreate;

        $id = $this->getNextId();
        $ingredient->setId($id);

        file_put_contents(
            $this->dataDir . 'ingredient-' . $id . '.json',
            json_encode($ingredient)
        );

        return $id;
    }
}