<?php

namespace App\Recipe;

class RecipeHydratorTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->sut = new RecipeHydrator;
    }

    /**
     * @test
     */
    public function itShouldReturnARecipeObjectWhenTheCorrectDataIsSupplied()
    {
        $expectedRecipe = new Recipe(
            'Ham Sandwich',
            'A meal for the lazy man',
            'Make it.',
            1
        );

        $recipe = $this->sut->hydrate(
            (object) [
                'name' => 'Ham Sandwich',
                'description' => 'A meal for the lazy man',
                'method' => 'Make it.',
                'servings' => 1
            ]
        );

        $this->assertEquals(
            $expectedRecipe,
            $recipe
        );
    }

    /**
     * @test
     */
    public function itShouldSetAnIdIfItIsProvided()
    {
        $recipe = $this->sut->hydrate(
            (object) [
                'id' => 12,
                'name' => 'asdf',
                'description' => 'asdf',
                'method' => 'asdf',
                'servings' => 123
            ]
        );

        $this->assertEquals(12, $recipe->getId());
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage A name, description, method and servings is required to build a Recipe
     */
    public function itShouldThrowAnExceptionIfTheRequiredArgumentsAreNotProvided()
    {
        $this->sut->hydrate(
            (object) [
                'name' => 'asdf',
                'description' => 'asdf'
            ]
        );
    }
}