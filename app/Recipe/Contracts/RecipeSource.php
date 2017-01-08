<?php

namespace App\Recipe\Contracts;

use stdClass;
use App\Recipe\Recipe;
use App\Ingredient\Ingredient;

interface RecipeSource
{
    public function findById(int $recipeId) : ?stdClass;

    public function findAll() : array;

    public function persistRecipe(Recipe $recipeToCreate) : int;

    public function addIngredientToRecipe($recipeId, Ingredient $Ingredient, $amount) : bool;
}