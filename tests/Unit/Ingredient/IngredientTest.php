<?php

namespace App\Ingredient;

class IngredientTest extends \PHPUnit\Framework\TestCase
{
    private $sut;

    public function setUp()
    {
        $this->sut = new Ingredient(
            'Bread'
        );
    }

    /**
     * @test
     */
    public function itShouldSetTheName()
    {
        $this->assertEquals(
            'Bread',
            $this->sut->getName()
        );
    }

    /**
     * @test
     */
    public function itShouldAllowAnOptionalIdToBeSet()
    {
        $this->sut->setId(20);

        $this->assertEquals(
            20,
            $this->sut->getId()
        );
    }

    /**
     * @test
     * @expectedException DomainException
     * @expectedException The id should not be changed once it has been set
     */
    public function itShouldNotAllowAnIdToBeChangedOnceItHasBeenSet()
    {
        $this->sut->setId(2)
            ->setId(20);
    }

    /**
     * @test
     */
    public function itShouldSerializeIntoAJsonObject()
    {
        $this->sut->setId(1);
        $json = json_encode($this->sut);

        $expectedJson = json_encode((object) [
            'id' => 1,
            'name' => 'Bread'
        ]);

        $this->assertEquals(
            $expectedJson,
            $json
        );
    }
}