<?php

namespace App\Recipe;

use App\Http\Controller;
use Illuminate\Http\Request;
use Illuminate\Contracts\Validation\Factory as Validator;
use Symfony\Component\HttpFoundation\JsonResponse;

final class RecipeController extends Controller
{
    private $recipeRepo;

    private $hydrator;

    public function __construct(
        RecipeRepository $recipeRepo,
        RecipeHydrator $hydrator,
        Validator $validator
    ) {
        $this->recipeRepo = $recipeRepo;
        $this->hydrator = $hydrator;
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

        $recipeToCreate = $this->hydrator->hydrate(
            (object) $request->all()
        );

        $newRecipe = $this->recipeRepo->createRecipe(
            $recipeToCreate
        );

        return $this->success($newRecipe);
    }
}
