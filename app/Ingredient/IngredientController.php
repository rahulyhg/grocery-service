<?php

namespace App\Ingredient;

use App\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Factory as Validator;

final class IngredientController extends Controller
{
    private $ingredientRepo;

    private $hydrator;

    public function __construct(
        IngredientRepository $ingredientRepo,
        IngredientHydrator $hydrator,
        Validator $validator
    ) {
        $this->ingredientRepo = $ingredientRepo;
        $this->hydrator = $hydrator;
        $this->validator = $validator;
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
        $validator = $this->validator->make(
            $request->all(),
            [
                'name' => 'required|string'
            ]
        );

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }

        $ingredientToCreate = $this->hydrator->hydrate(
            (object) $request->all()
        );

        $newIngredient = $this->ingredientRepo->createIngredient(
            $ingredientToCreate
        );

        return $this->success($newIngredient);
    }
}