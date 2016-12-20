<?php

use App\Recipe\Contracts\RecipeSource;
use App\Recipe\Recipe;

class CreateRecipeTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnACompleteRecipeWithTheCorrectParameters()
    {
        $this->configureStubRecipeSource();

        $response = $this->call(
            'POST',
            '/recipes',
            [
                'name' => 'A new recipe',
                'description' => 'A magical new recipe to trial.',
                'method' => 'Magic! We are not too sure yet.',
                'servings' => 4
            ]
        );

        $this->assertEquals(
            (object) [
                'id' => 1337,
                'name' => 'A new recipe',
                'description' => 'A magical new recipe to trial.',
                'method' => 'Magic! We are not too sure yet.',
                'servings' => 4,
                'ingredients' => []
            ],
            $response->getData()->data
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAnErrorIfInvalidParametersAreProvided()
    {
        $response = $this->call(
            'POST',
            '/recipes'
        );

        $this->assertEquals(
            (object) [
                'code' => 'error',
                'data' => (object) [
                    'name' => ['The name field is required.'],
                    'description' => ['The description field is required.'],
                    'method' => ['The method field is required.'],
                    'servings' => ['The servings field is required.'],
                ]
            ],
            $response->getData()
        );
    }

    private function configureStubRecipeSource()
    {
        $stubRecipeSource = function () {
            return new class implements RecipeSource {
                public function findById(int $recipeId) : ?stdClass
                {
                }

                public function findAll() : array
                {
                    return [];
                }

                public function persistRecipe(Recipe $recipeToCreate) : int
                {
                    return 1337;
                }
            };
        };

        $this->app->bind(
            RecipeSource::class,
            $stubRecipeSource
        );
    }
}