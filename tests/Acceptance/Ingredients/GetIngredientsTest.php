<?php

use App\Ingredient\Contracts\IngredientSource;
use App\Ingredient\Ingredient;

class GetIngredientsTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnAllIngredients()
    {
        $this->configureStubIngredientSource();

        $response = $this->call(
            'GET',
            '/ingredients'
        );

        $this->assertEquals(
            $this->getFixture('ingredients/happy-path-response'),
            $response->getData()
        );
    }

    /**
     * @test
     */
    public function itShouldRetrieveASingleIngredientById()
    {
        $response = $this->call(
            'GET',
            '/ingredients/1'
        );

        $this->assertEquals(
            $this->getFixture('ingredients/happy-path-single-response'),
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
            '/ingredients/1337'
        );

        $this->assertEquals(
            (object) [
                'code' => 'error',
                'data' => ['Could not find the ingredient requested']
            ],
            $response->getData()
        );
    }

    private function configureStubIngredientSource()
    {
        $stubIngredientSource = function () {
            return new class implements IngredientSource {
                public function findAll() : array
                {
                    return [
                        (object) [
                            'id' => 1,
                            'name' => 'Bread'
                        ],
                        (object) [
                            'id' => 2,
                            'name' => 'Ham'
                        ],
                        (object) [
                            'id' => 3,
                            'name' => 'Butter'
                        ],
                    ];
                }

                public function findById(int $id) : ?stdClass
                {
                    return (object) [];
                }

                public function persistIngredient(Ingredient $ingredient) : int
                {
                    return 1337;
                }
            };
        };

        $this->app->bind(
            IngredientSource::class,
            $stubIngredientSource
        );
    }
}