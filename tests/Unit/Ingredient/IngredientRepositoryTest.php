<?php

namespace App\Ingredient;

use App\Ingredient\Contracts\IngredientSource;

class IngredientRepositoryTest extends \PHPUnit_Framework_TestCase
{
    private $sut;

    private $source;

    public function setUp()
    {
        $this->hydrator = $this->createMock(IngredientHydrator::class);
        $this->source = $this->createMock(IngredientSource::class);

        $this->sut = new IngredientRepository(
            $this->hydrator,
            $this->source
        );
    }

    /**
     * @test
     */
    public function itShouldBeInstantiable()
    {
        $this->assertInstanceOf(
            'App\Ingredient\IngredientRepository',
            $this->sut
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAllIngredientsAsIngredientModels()
    {
        $this->source->expects($this->once())
            ->method('findAll')
            ->willReturn(
                [
                    (object) ['id' => 1, 'name' => 'Bread'],
                    (object) ['id' => 2, 'name' => 'Wine'],
                    (object) ['id' => 3, 'name' => 'Butter']
                ]
            );

        $this->hydrator->expects($this->at(0))
            ->method('hydrate')
            ->willReturn((new Ingredient('Bread'))->setId(1));
        
        $this->hydrator->expects($this->at(1))
            ->method('hydrate')
            ->willReturn((new Ingredient('Wine'))->setId(2));

        $this->hydrator->expects($this->at(2))
            ->method('hydrate')
            ->willReturn((new Ingredient('Butter'))->setId(3));

        $ingredients = $this->sut->findAll();

        $this->assertEquals(
            [
                (new Ingredient('Bread'))->setId(1),
                (new Ingredient('Wine'))->setId(2),
                (new Ingredient('Butter'))->setId(3),
            ],
            $ingredients
        );
    }

    /**
     * @test
     */
    public function itShouldReturnAnEmptyArrayIfNoIngredientsAreReturned()
    {
        $this->source->expects($this->once())
            ->method('findAll')
            ->willReturn([]);

        $this->assertEquals(
            [],
            $this->sut->findAll()
        );
    }

    /**
     * @test
     */
    public function itShouldRetrieveASingleIngredientById()
    {
        $expectedIngredient = new Ingredient('Bread');
        $expectedIngredient->setId(12);

        $this->source->expects($this->once())
            ->method('findById')
            ->willReturn((object) ['id' => 12, 'Bread']);

        $this->hydrator->expects($this->once())
            ->method('hydrate')
            ->willReturn($expectedIngredient);

        $ingredient = $this->sut->findById(12);

        $this->assertEquals(
            $expectedIngredient,
            $ingredient
        );
    }

    /**
     * @test
     */
    public function itShouldReturnNullIfNoIngredientCanBeFound()
    {
        $ingredient = $this->sut->findById(1337);

        $this->assertNull($ingredient);
    }
}