<?php declare(strict_types=1);

namespace App\Recipe;

class Recipe implements \JsonSerializable
{
    private $id;

    private $name;

    private $description;

    private $method;

    private $servings;

    private $ingredients = [];

    public function __construct(
        string $name,
        string $description,
        string $method,
        int $servings
    ) {
        $this->name = $name;
        $this->description = $description;
        $this->method = $method;
        $this->servings = $servings;
    }

    public function setId(int $id) : Recipe
    {
        if (!is_null($this->id)) {
            throw new \DomainException('The id should not be changed once it has been set.');
        }

        $this->id = $id;

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getMethod() : string
    {
        return $this->method;
    }

    public function getServings() : int
    {
        return $this->servings;
    }

    public function getIngredients() : array
    {
        return $this->ingredients;
    }

    public function addIngredient(
        RecipeIngredient $recipeIngredient
    ) : Recipe {
        $this->ingredients[] = $recipeIngredient;

        return $this;
    }

    public function jsonSerialize()
    {
        $vars = get_object_vars($this);

        if (is_null($vars['id'])) {
            unset($vars['id']);
        }

        return $vars;
    }
}
