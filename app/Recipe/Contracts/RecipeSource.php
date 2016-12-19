<?php

namespace App\Recipe\Contracts;

use stdClass;
use App\Recipe\Recipe;

interface RecipeSource
{
    public function findById(int $recipeId) : ?stdClass;

    public function findAll() : array;

    public function persistRecipe(Recipe $recipeToCreate) : int;
}