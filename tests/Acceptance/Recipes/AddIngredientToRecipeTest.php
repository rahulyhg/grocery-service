<?php

class AddIngredientToRecipeTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnAFullRecipeIngredientWhenTheCorrectDataIsPassed()
    {
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
}