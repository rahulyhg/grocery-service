<?php

namespace App\Recipe;

use App\Recipe\Contracts\RecipeSource;

class RecipeRepositoryTest extends \PHPUnit\Framework\TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->source = $this->createMock(RecipeSource::class);
        $this->hydrator = $this->createMock(RecipeHydrator::class);

        $this->sut = new RecipeRepository(
            $this->source,
            $this->hydrator
        );
    }

    /**
     * @test
     */
    public function itBeInstantiable()
    {
        $this->assertInstanceOf(
            '\App\Recipe\RecipeRepository',
            $this->sut
        );
    }

    /**
     * @test
     */
    public function whenFindingARecipeByIdItShouldCallASourceAndAHydrator()
    {
        $recipeData = (object) [
            'id' => 1,
            'name' => 'Ham Toastie',
            'description' => 'lazy mans meal',
            'method' => 'make it.',
            'servings' => 1
        ];

        $this->source->expects($this->once())
            ->method('findById')
            ->with($this->equalTo(1))
            ->willReturn($recipeData);

        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->with($this->equalTo($recipeData));

        $this->sut->findById(1);
    }

    /**
     * @test
     */
    public function whenFindingAllRecipesItShouldCallASourceOneAndTheHydratorMultipleTimes()
    {
        $this->source->expects($this->once())
            ->method('findAll')
            ->willReturn([
                (object) [],
                (object) []]
            );

        $this->hydrator->expects($this->exactly(2))
            ->method('hydrate');

        $this->sut->findAll();
    }

    /**
     * @test
     * @expectedException \App\Recipe\RecipeNotFoundException
     */
    public function whenRequestingARecipeByIdThatDoesNotExistItShouldThrowAnException()
    {
        $this->source->method('findById')
            ->willReturn(null);

        $this->sut->findById(1337);
    }

    /**
     * @test
     */
    public function itShouldInteractWithTheHydratorProperly()
    {
        $this->setupExampleRecipeExpectation();

        $sut = new RecipeRepository(
            $this->source,
            new RecipeHydrator
        );

        $recipe = $sut->findById(1);

        $this->assertEquals(
            $this->buildExampleRecipe(),
            $recipe
        );
    }

    /**
     * @test
     */
    public function itShouldCreateARecipeFromARecipeObject()
    {
        $recipeToCreate = $this->buildExampleNewRecipe(); 

        $this->source->expects($this->once())
            ->method('persistRecipe')
            ->with($this->equalTo($recipeToCreate))
            ->willReturn(123);

        $createdRecipe = $this->sut->createRecipe($recipeToCreate);

        $this->assertEquals(
            123,
            $createdRecipe->getId()
        );
    }

    private function buildExampleRecipe()
    {
        $expectedRecipe = new Recipe(
            'asdf',
            'asdfa',
            'aaaaa',
            1
        );

        return $expectedRecipe->setId(12);
    }

    private function buildExampleNewRecipe()
    {
        return new Recipe(
            'asdf',
            'asdfa',
            'aaaaa',
            1
        );
    }

    private function setupExampleRecipeExpectation()
    {
        $this->source->method('findById')
            ->willReturn((object) [
                'id' => 12,
                'name' => 'asdf',
                'description' => 'asdfa',
                'method' => 'aaaaa',
                'servings' => 1
            ]);
    }
}
