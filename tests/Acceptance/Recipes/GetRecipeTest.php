<?php

use App\Recipe\Contracts\RecipeSource;
use App\Recipe\Recipe;

class GetRecipeTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnA200()
    {
        $response = $this->call(
            'GET',
            '/recipes'
        );

        $this->assertEquals(
            200,
            $response->getStatusCode()
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAnArrayOfRecipes()
    {
        $this->configureStubRecipeSource();

        $response = $this->call(
            'GET',
            '/recipes'
        );

        $this->assertEquals(
            $this->getFixture('recipes/happy-path-response'),
            $response->getData()
        );
    }

    /**
     * @test
     */
    public function itShouldReturnASingleRecipe()
    {
        $response = $this->call(
            'GET',
            '/recipes/1'
        );

        $this->assertEquals(
            $this->getFixture('recipes/happy-path-single-recipe'),
            $response->getData()
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAnErrorIfARecipeDoesNotExist()
    {
        $response = $this->call(
            'GET',
            '/recipes/1337'
        );

        $this->assertEquals(
            (object) [
                'code' => 'error',
                'data' => [
                    'The recipe requested was not found'
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
                    $baseDir = __DIR__ . '/../../../app/Recipe/data/';
                    $recipe1 = json_decode(file_get_contents($baseDir.'recipe-1.json'));
                    $recipe2 = json_decode(file_get_contents($baseDir.'recipe-2.json'));

                    return [$recipe1, $recipe2];
                }

                public function persistRecipe(Recipe $recipeToCreate) : int
                {
                    return 1;
                }
            };
        };

        $this->app->bind(
            RecipeSource::class,
            $stubRecipeSource
        );
    }
}
