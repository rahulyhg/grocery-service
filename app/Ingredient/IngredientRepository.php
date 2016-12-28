<?php

namespace App\Ingredient;

use App\Ingredient\Contracts\IngredientSource;

class IngredientRepository
{
    private $hydrator;

    private $source;

    public function __construct(
        IngredientHydrator $hydrator,
        IngredientSource $source
    ) {
        $this->hydrator = $hydrator;
        $this->source = $source;
    }

    public function findAll() : array
    {
        $ingredientData = $this->source->findAll();

        $ingredients = array_map(function ($ingredient) {
            return $this->hydrator->hydrate($ingredient);
        }, $ingredientData);

        return array_values($ingredients);
    }

    public function findById(int $id) : ?Ingredient
    {
        $ingredient = $this->source->findById($id);

        if (is_null($ingredient)) {
            return null;
        }

        return $this->hydrator->hydrate($ingredient);
    }

    public function createIngredient(
        Ingredient $ingredientToCreate
    ) : Ingredient {
        $ingredientId = $this->source->persistIngredient(
            $ingredientToCreate
        );

        return $ingredientToCreate->setId($ingredientId);
    }
}