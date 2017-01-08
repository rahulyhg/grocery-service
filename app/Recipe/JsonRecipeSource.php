<?php

namespace App\Recipe;

use stdClass;
use App\Traits\IsJsonSource;
use App\Recipe\Contracts\RecipeSource;
use App\Ingredient\Ingredient;

class JsonRecipeSource implements RecipeSource
{
    use IsJsonSource;

    public function __construct()
    {
        $this->dataDir = __DIR__ . '/data/';
    }

    public function findById(int $recipeId) : ?stdClass
    {
        $file = $this->dataDir . 'recipe-' . $recipeId . '.json';

        if (!file_exists($file)) {
            return null;
        }

        return json_decode(
            file_get_contents($file)
        );
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

    public function persistRecipe(Recipe $recipeToCreate) : int
    {
        // bit of a hack - but this is a temp source so who cares.
        $recipe = clone $recipeToCreate;

        $id = $this->getNextId();
        $recipe->setId($id);

        file_put_contents(
            $this->dataDir . 'recipe-' . $id . '.json',
            json_encode($recipe)
        );

        return $id;
    }

    public function addIngredientToRecipe(
        $recipeId,
        Ingredient $ingredient,
        $amount
    ) : bool {
        $recipe = $this->findById($recipeId);
        $recipe->ingredients[] = $ingredient;

        return $this->updateRecipe($recipe);
    }

    private function updateRecipe(stdClass $recipe) : bool
    {
        $file = $this->dataDir . 'recipe-' . $recipe->id . '.json';

        if (!file_exists($file)) {
            return false;
        }

        file_put_contents(
            $file,
            json_encode($recipe, JSON_PRETTY_PRINT)
        );

        return true;
    }
}