<?php

namespace App\Recipe;

use App\Ingredient\IngredientHydrator;
use App\Ingredient\Ingredient;

class RecipeHydratorTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        $this->ingredientHydrator = $this->getMockBuilder(IngredientHydrator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new RecipeHydrator(
            $this->ingredientHydrator
        );
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

    /**
     * @test
     */
    public function itShouldHydrateIngredientsIfTheyArePresent()
    {
        $expectedIngredients = $this->getExampleIngredients();

        $this->ingredientHydrator->expects($this->at(0))
            ->method('hydrate')
            ->willReturn(
                $expectedIngredients[0]->getDetails()
            );
        $this->ingredientHydrator->expects($this->at(1))
            ->method('hydrate')
            ->willReturn(
                $expectedIngredients[1]->getDetails()
            );

        $recipe = $this->sut->hydrate(
            (object) [
                'id' => 12,
                'name' => 'asdf',
                'description' => 'asdf',
                'method' => 'asdf',
                'servings' => 123,
                'ingredients' => [
                    (object) [
                        'details' => (object) [
                            'id' => 1,
                            'name' => 'Bread'
                        ],
                        'amount' => '4 slices'
                    ],
                    (object) [
                        'details' => (object) [
                            'id' => 2,
                            'name' => 'Ham'
                        ],
                        'amount' => '2 slices'
                    ],
                ]
            ]
        );

        $this->assertEquals(
            $expectedIngredients,
            $recipe->getIngredients()
        );
    }

    private function getExampleIngredients()
    {
        $ingredient1 = new RecipeIngredient(
            (new Ingredient('Bread'))->setId(1),
            '4 slices'
        );
        $ingredient2 = new RecipeIngredient(
            (new Ingredient('Ham'))->setId(2),
            '2 slices'
        );

        return [$ingredient1, $ingredient2];
    }
}