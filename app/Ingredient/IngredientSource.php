<?php

namespace App\Ingredient;

use stdClass;

interface IngredientSource
{
    public function findAll() : array;

    public function findById(int $id) : ?stdClass;
}