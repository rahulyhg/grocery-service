<?php

use App\Recipe\Contracts\RecipeSource;
use App\Recipe\Recipe;
use App\Recipe\RecipeIngredient;

class AddIngredientToRecipeTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnAFullRecipeIngredientWhenTheCorrectDataIsPassed()
    {
        $this->configureStubRecipeSource();

        $response = $this->call(
            'PUT',
            '/recipes/1/ingredients',
            [
                'ingredient_id' => 1,
                'amount' => '4 slices'
            ]
        );

        $this->assertEquals(
            (object) [
                'code' => 'success',
                'data' => (object) [
                    'details' => (object) [
                        'id' => 1,
                        'name' => 'Bread'
                    ],
                    'amount' => '4 slices'
                ]
            ],
            $response->getData()
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAValidationErrorIfTheIncorrectDetailsArePassed()
    {

    }

    /**
     * @test
     */
    public function itShouldReturnAnErrorIfTheIngredientDoesNotExist()
    {
        
    }

    private function configureStubRecipeSource()
    {
        $stubRecipeSource = function () {
            return new class implements RecipeSource {
                public function findById(int $recipeId) : ?stdClass
                {
                    return null;
                }

                public function findAll() : array
                {
                    return [];
                }

                public function persistRecipe(Recipe $recipeToCreate) : int
                {
                    return 1;
                }

                public function addIngredientToRecipe(
                    $recipeId,
                    RecipeIngredient $recipeIngredient
                ) : bool {
                    return true;
                }
            };
        };

        $this->app->bind(
            RecipeSource::class,
            $stubRecipeSource
        );
    }
}