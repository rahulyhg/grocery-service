<?php

namespace App\Recipe\Contracts;

use stdClass;
use App\Recipe\Recipe;
use App\Recipe\RecipeIngredient;

interface RecipeSource
{
    public function findById(int $recipeId) : ?stdClass;

    public function findAll() : array;

    public function persistRecipe(Recipe $recipeToCreate) : int;

    public function addIngredientToRecipe(
        $recipeId,
        RecipeIngredient $recipeIngredient
    ) : bool;
}