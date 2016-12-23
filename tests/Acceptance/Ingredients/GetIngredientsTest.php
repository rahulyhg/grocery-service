<?php

class GetIngredientsTest extends TestCase
{
    /**
     * @test
     */
    public function itShouldReturnAllIngredients()
    {
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
}