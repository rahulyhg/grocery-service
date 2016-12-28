<?php

use App\Ingredient\Contracts\IngredientSource;
use App\Ingredient\Ingredient;

class CreateIngredientTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnACompleteIngredientWithTheCorrectParameters()
    {
        $this->configureStubIngredientSource();

        $response = $this->call(
            'POST',
            '/ingredients',
            [
                'name' => 'Test Ingredient'
            ]
        );
        
        $this->assertEquals(
            (object) [
                'code' => 'success',
                'data' => (object) [
                    'id' => 1337,
                    'name' => 'Test Ingredient'
                ]
            ],
            $response->getData()
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAnErrorIfInvalidParametersAreProvided()
    {
        $response = $this->call(
            'POST',
            '/ingredients',
            [
            ]
        );

        $this->assertEquals(
            (object) [
                'code' => 'error',
                'data' => (object) [
                    'name' => ['The name field is required.']
                ]
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
                    return [];
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