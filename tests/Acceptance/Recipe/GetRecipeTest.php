<?php

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
                "code" => "error",
                "messages" => [
                    "The recipe requested was not found"
                ]
            ],
            $response->getData()
        );
    }
}
