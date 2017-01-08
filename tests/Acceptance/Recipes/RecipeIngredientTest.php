<?php

namespace App\Recipe;

use App\Ingredient\Ingredient;

class RecipeIngredientTest extends \PHPUnit\Framework\TestCase
{
    private $sut;

    public function setUp()
    {
        $ingredient = (new Ingredient('Bread'))
            ->setId(1);

        $this->sut = new RecipeIngredient(
            $ingredient,
            '4 slices'
        );
    }

    /**
     * @test
     */
    public function itShouldSetTheIngredientDetails()
    {
        $expectedIngredient = (new Ingredient('Bread'))
            ->setId(1);

        $this->assertEquals(
            $expectedIngredient,
            $this->sut->getDetails()
        );
    }

    /**
     * @test
     */
    public function itShouldSetTheAmount()
    {
        $this->assertEquals(
            '4 slices',
            $this->sut->getAmount()
        );
    }
}