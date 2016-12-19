<?php

class CreateRecipeTest extends TestCase
{
    /**
     * TODO: I want the source to predictably return the same
     * id - instead of just asserting against the stucture.
     * I'm thinking creating a "stub" source might solve this.
     * @test
     */
    public function itShouldReturnACompleteRecipeWithTheCorrectParameters()
    {
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
            [
                'id',
                'name',
                'description',
                'method',
                'servings',
                'ingredients'
            ],
            array_keys((array) $response->getData()->data)
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
}