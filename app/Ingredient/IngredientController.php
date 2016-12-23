<?php

namespace App\Ingredient;

use App\Http\Controller;

final class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = [
            (new Ingredient('Bread'))->setId(1),
            (new Ingredient('Ham'))->setId(2),
            (new Ingredient('Butter'))->setId(3)
        ];

        return $this->success($ingredients);
    }

    public function show($id)
    {
        if ($id != 1) {
            return $this->notFound('Could not find the ingredient requested');
        }

        $ingredient = new Ingredient('Bread');
        $ingredient->setId(1);

        return $this->success($ingredient);
    }
}