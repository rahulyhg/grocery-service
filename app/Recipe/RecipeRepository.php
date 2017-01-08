<?php

namespace App\Recipe;

use App\Ingredient\Ingredient;
use App\Recipe\Contracts\RecipeSource;
use App\Ingredient\IngredientRepository;
use App\Ingredient\IngredientNotFoundException;

class RecipeRepository
{
    private $source;

    private $ingredientRepo;

    private $hydrator;

    public function __construct(
        RecipeSource $source,
        IngredientRepository $ingredientRepo,
        RecipeHydrator $hydrator
    ) {
        $this->source = $source;
        $this->ingredientRepo = $ingredientRepo;
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

    public function addIngredientToRecipe(
        int $recipeId,
        int $ingredientId,
        string $amount
    ) : ?RecipeIngredient {
        $ingredient = $this->ingredientRepo->findById(
            $ingredientId
        );

        $success = $this->source->addIngredientToRecipe(
            $recipeId,
            $ingredient,
            $amount
        );

        if (!$success) {
            return null;
        }

        return new RecipeIngredient(
            $ingredient,
            $amount
        );
    }
}