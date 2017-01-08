<?php

namespace App\Recipe;

use App\Ingredient\Ingredient;
use App\Recipe\Contracts\RecipeSource;
use App\Ingredient\IngredientRepository;
use App\Ingredient\IngredientHydrator;

class RecipeRepositoryTest extends \PHPUnit\Framework\TestCase
{
    const TEST_RECIPE_ID = 1;
    const TEST_INGREDIENT_ID = 1;
    const TEST_AMOUNT = '4 slices';

    private $source;

    private $ingredientRepo;

    private $sut;

    public function setUp()
    {
        parent::setUp();

        $this->source = $this->createMock(RecipeSource::class);

        $this->ingredientRepo = $this->getMockBuilder(IngredientRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->hydrator = $this->getMockBuilder(RecipeHydrator::class)
            ->disableOriginalConstructor()
            ->getMock();

        $this->sut = new RecipeRepository(
            $this->source,
            $this->ingredientRepo,
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

        $hydrator = new RecipeHydrator(
            new IngredientHydrator()
        );

        $sut = new RecipeRepository(
            $this->source,
            $this->ingredientRepo,
            $hydrator
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

    /**
     * @test
     */
    public function itShouldAddAnIngredientToARecipe()
    {
        $expectedRecipeIngredient = $this->setUpAddIngredientToRecipe(
            self::TEST_AMOUNT
        );

        $recipeIngredient = $this->sut->addIngredientToRecipe(
            self::TEST_RECIPE_ID,
            self::TEST_INGREDIENT_ID,
            self::TEST_AMOUNT
        );

        $this->assertEquals(
            $expectedRecipeIngredient,
            $recipeIngredient
        );
    }

    /**
     * @test
     */
    public function itShouldReturnNullIfItEncountersAnIssueWhenAddingAnIngredientToARecipe()
    {
        $this->setUpAddIngredientToRecipe(
            self::TEST_AMOUNT,
            false
        );

        $recipeIngredient = $this->sut->addIngredientToRecipe(
            self::TEST_RECIPE_ID,
            self::TEST_INGREDIENT_ID,
            self::TEST_AMOUNT
        );
        
        $this->assertNull($recipeIngredient);
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

    private function setUpAddIngredientToRecipe(
        string $amount,
        bool $success = true
    ) {
        $ingredient = (new Ingredient('Bread'))
            ->setId(1);

        $recipeIngredient = new RecipeIngredient(
            $ingredient,
            $amount
        );

        $this->ingredientRepo->expects($this->once())
            ->method('findById')
            ->willReturn($ingredient);

        $this->source->expects($this->once())
            ->method('addIngredientToRecipe')
            ->with(
                $this->equalTo(self::TEST_RECIPE_ID),
                $this->equalTo($recipeIngredient)
            )
            ->willReturn($success);

        return $recipeIngredient;
    }
}
