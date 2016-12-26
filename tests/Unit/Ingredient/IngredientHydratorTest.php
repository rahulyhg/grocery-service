<?php

namespace App\Ingredient;

class IngredientHydratorTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->sut = new IngredientHydrator;
    }

    /**
     * @test
     */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf(
            'App\Ingredient\IngredientHydrator',
            $this->sut
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAnIngredientObjectWhenTheCorrectDataIsSupplied()
    {
        $expectedIngredient = new Ingredient('Bread');

        $ingredient = $this->sut->hydrate(
            (object) [
                'name' => 'Bread'
            ]
        );

        $this->assertEquals(
            $expectedIngredient,
            $ingredient
        );
    }

    /**
     * @test
     */
    public function itShouldSetAnIdIfItIsProvided()
    {
        $expectedIngredient = new Ingredient('Bread');
        $expectedIngredient->setId(12);

        $ingredient = $this->sut->hydrate(
            (object) [
                'id' => 12,
                'name' => 'Bread'
            ]
        );

        $this->assertEquals(
            $expectedIngredient,
            $ingredient
        );
    }

    /**
     * @test
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage A name is required to build an Ingredient
     */
    public function itShouldThrowAnExceptionIfTheRequiredArgumentsAreNotProvided()
    {
        $this->sut->hydrate(
            (object) [
                'id' => 422
            ]
        );
    }
}