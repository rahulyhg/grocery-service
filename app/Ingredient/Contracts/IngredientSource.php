<?php

namespace App\Ingredient\Contracts;

use App\Ingredient\Ingredient;

use stdClass;

interface IngredientSource
{
    public function findAll() : array;

    public function findById(int $id) : ?stdClass;

    public function persistIngredient(Ingredient $ingredient) : int;
}