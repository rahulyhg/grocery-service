<?php

namespace App\Recipe;

use App\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Factory as Validator;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Ingredient\IngredientHydrator;

final class RecipeController extends Controller
{
    private $recipeRepo;

    private $recipeHydrator;

    private $ingredientHydrator;

    public function __construct(
        RecipeRepository $recipeRepo,
        RecipeHydrator $recipeHydrator,
        IngredientHydrator $ingredientHydrator,
        Validator $validator
    ) {
        $this->recipeRepo = $recipeRepo;
        $this->recipeHydrator = $recipeHydrator;
        $this->ingredientHydrator = $ingredientHydrator;
        $this->validator = $validator;
    }

    public function index()
    {
        $recipes = $this->recipeRepo->findAll();

        return $this->success($recipes);
    }

    public function show($id)
    {
        try {
            $recipe = $this->recipeRepo->findById($id);
        } catch (RecipeNotFoundException $e) {
            return $this->notFound(
                'The recipe requested was not found'
            );
        }

        return $this->success($recipe);
    }

    public function store(Request $request)
    {
        $validator = $this->validator->make(
            $request->all(),
            [
                'name' => 'required|string',
                'description' => 'required|string',
                'method' => 'required|string',
                'servings' => 'required|integer'
            ]
        );

        if ($validator->fails()) {
            return $this->badRequest($validator->errors());
        }

        $recipeToCreate = $this->recipeHydrator->hydrate(
            (object) $request->all()
        );

        $newRecipe = $this->recipeRepo->createRecipe(
            $recipeToCreate
        );

        return $this->success($newRecipe);
    }

    public function addIngredient(Request $request, $recipeId)
    {
        $recipeIngredient = $this->recipeRepo->addIngredientToRecipe(
            $recipeId,
            $request->get('ingredient_id'),
            $request->get('amount')
        );

        return $this->success($recipeIngredient);
    }
}
