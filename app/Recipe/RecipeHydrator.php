<?php

namespace App\Recipe;

use stdClass;
use App\Ingredient\IngredientHydrator;

class RecipeHydrator
{
    private $ingredientHydrator;

    public function __construct(
        IngredientHydrator $ingredientHydrator
    ) {
        $this->ingredientHydrator = $ingredientHydrator;
    }

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

        if (isset($data->ingredients)) {
            $this->addIngredients($recipe, $data->ingredients);
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

    private function addIngredients(
        Recipe $recipe,
        array $ingredients
    ) {
        foreach ($ingredients as $ingredient) {
            $ingredientToAdd = $this->ingredientHydrator->hydrate(
                $ingredient->details
            );

            $recipeIngredient = new RecipeIngredient(
                $ingredientToAdd,
                $ingredient->amount
            );

            $recipe->addIngredient($recipeIngredient);
        }
    }
}