<?php

namespace App\Ingredient;

use App\Http\Controller;

final class IngredientController extends Controller
{
    private $ingredientRepo;

    public function __construct(
        IngredientRepository $ingredientRepo
    ) {
        $this->ingredientRepo = $ingredientRepo;
    }

    public function index()
    {
        $ingredients = $this->ingredientRepo->findAll();

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