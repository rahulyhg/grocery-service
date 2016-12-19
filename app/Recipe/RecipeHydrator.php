<?php

namespace App\Recipe;

use stdClass;

class RecipeHydrator
{
    public function hydrate(stdClass $data) : Recipe
    {
        $this->validate($data);

        $recipe = new Recipe(
            $data->name,
            $data->description,
            $data->method,
            $data->servings
        );

        if (isset($data->id)) {
            $recipe->setId($data->id);
        }

        return $recipe;
    }

    private function validate(stdClass $data)
    {
        if (
            isset($data->name) &&
            isset($data->description) &&
            isset($data->method) &&
            isset($data->servings)
        ) {
            return true;
        }

        throw new \InvalidArgumentException(
            'A name, description, method and servings is required to build a Recipe'
        );
    }
}