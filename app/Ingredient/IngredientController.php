<?php

namespace App\Ingredient;

use App\Http\Controller;
use Illuminate\Http\Request;

final class IngredientController extends Controller
{
    private $ingredientRepo;

    private $hydrator;

    public function __construct(
        IngredientRepository $ingredientRepo,
        IngredientHydrator $hydrator
    ) {
        $this->ingredientRepo = $ingredientRepo;
        $this->hydrator = $hydrator;
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

    public function store(Request $request)
    {
        $ingredientToCreate = $this->hydrator->hydrate(
            (object) $request->all()
        );

        $newIngredient = $this->ingredientRepo->createIngredient(
            $ingredientToCreate
        );

        return $this->success($newIngredient);
    }
}