<?php

namespace App\Ingredient;

use stdClass;
use InvalidArgumentException;

class IngredientHydrator
{
    public function hydrate(stdClass $data)
    {
        if (!isset($data->name)) {
            throw new InvalidArgumentException(
                'A name is required to build an Ingredient'
            );
        }

        $ingredient = new Ingredient($data->name);

        if (isset($data->id)) {
            $ingredient->setId($data->id);
        }

        return $ingredient;
    }
}