<?php

namespace App\Recipe;

use App\Recipe\Contracts\RecipeSource;

class RecipeRepository
{
    public function __construct(
        RecipeSource $source,
        RecipeHydrator $hydrator
    ) {
        $this->source = $source;
        $this->hydrator = $hydrator;
    }

    public function findById(int $recipeId) : Recipe
    {
        $recipeData = $this->source->findById($recipeId);

        if (is_null($recipeData)) {
            throw new RecipeNotFoundException;
        }

        return $this->hydrator->hydrate($recipeData);
    }

    public function findAll() : array
    {
        $recipeData = $this->source->findAll();

        $recipes = array_map(
            function ($recipe) {
                return $this->hydrator->hydrate($recipe);
            },
            $recipeData
        );

        return array_values($recipes);
    }

    public function createRecipe(Recipe $recipeToCreate) : Recipe
    {
        $recipeId = $this->source->persistRecipe(
            $recipeToCreate
        );

        $recipeToCreate->setId($recipeId);

        return $recipeToCreate;
    }
}