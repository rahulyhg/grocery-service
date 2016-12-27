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
        $ingredient = $this->ingredientRepo->findById($id);

        if (is_null($ingredient)) {
            return $this->notFound('Could not find the ingredient requested');
        }

        return $this->success($ingredient);
    }
}