<?php declare(strict_types=1);

namespace App\Recipe;

use PHPUnit\Framework\TestCase;
use App\Ingredient\Ingredient;

class RecipeTest extends TestCase
{
    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->sut = new Recipe(
            'Ham Sandwich',
            'A meal for the lazy',
            'You do not need a method for this',
            4
        );
    }

    /**
     * @test
     */
    public function itShouldSetTheName()
    {
        $this->assertEquals(
            'Ham Sandwich',
            $this->sut->getName()
        );
    }

    /**
    * @test
    */
    public function itShouldAllowAnOptionalIdToBeSet()
    {
        $this->sut->setId(2);

        $this->assertEquals(2, $this->sut->getId());
    }

    /**
    * @test
    * @expectedException DomainException
    * @expectedExceptionMessage The id should not be changed once it has been set.
    */
    public function itShouldNotAllowAnIdToBeChangedOnceItHasBeenSet()
    {
        $this->sut->setId(2)
            ->setId(3);
    }

    /**
    * @test
    */
    public function itShouldReturnTheDescription()
    {
        $this->assertEquals(
            'A meal for the lazy',
            $this->sut->getDescription()
        );
    }

    /**
    * @test
    */
    public function itShouldReturnTheMethod()
    {
        $this->assertEquals(
            'You do not need a method for this',
            $this->sut->getMethod()
        );
    }

    /**
    * @test
    */
    public function itShouldReturnTheNumberOfPeopleItServes()
    {
        $this->assertEquals(
            4,
            $this->sut->getServings()
        );
    }

    /**
    * @test
    */
    public function itShouldStartOffWithNoIngredients()
    {
        $this->assertCount(0, $this->sut->getIngredients());
    }

    /**
    * @test
    */
    public function itShouldBeAbleToAddAnIngredient()
    {
        $exampleRecipeIngredient = new RecipeIngredient(
            (new Ingredient('Bread'))->setId(1),
            '4 slices'
        );

        $this->sut->addIngredient($exampleRecipeIngredient);

        $this->assertCount(1, $this->sut->getIngredients());
    }

    /**
    * @test
    */
    public function itShouldBeAbleToAddManyIngredients()
    {
        $exampleRecipeIngredient = new RecipeIngredient(
            (new Ingredient('Bread'))->setId(1),
            '4 slices'
        );

        $this->sut->addIngredient($exampleRecipeIngredient)
            ->addIngredient($exampleRecipeIngredient);

        $this->assertCount(2, $this->sut->getIngredients());
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
            'name' => 'Ham Sandwich',
            'description' => 'A meal for the lazy',
            'method' => 'You do not need a method for this',
            'servings' => 4,
            'ingredients' => []
        ]);

        $this->assertEquals(
            $expectedJson,
            $json
        );
    }

    /**
    * @test
    */
    public function itShouldNotReturnAnIdInSerializedJsonIfOneIsNotSet()
    {
        $json = json_encode($this->sut);
        $expectedJson = json_encode((object) [
            'name' => 'Ham Sandwich',
            'description' => 'A meal for the lazy',
            'method' => 'You do not need a method for this',
            'servings' => 4,
            'ingredients' => []
        ]);

        $this->assertEquals(
            $expectedJson,
            $json
        );
    }
}
