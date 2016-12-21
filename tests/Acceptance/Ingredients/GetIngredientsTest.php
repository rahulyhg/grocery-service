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
}