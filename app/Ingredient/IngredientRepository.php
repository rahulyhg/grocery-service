<?php

namespace App\Ingredient;

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
        $ingredients = $this->source->findAll();

        return array_map(function ($ingredient) {
            return $this->hydrator->hydrate($ingredient);
        }, $ingredients);
    }

    public function findById(int $id) : ?Ingredient
    {
        $ingredient = $this->source->findById($id);

        if (is_null($ingredient)) {
            return null;
        }

        return $this->hydrator->hydrate($ingredient);
    }
}