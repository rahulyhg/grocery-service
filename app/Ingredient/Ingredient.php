<?php

namespace App\Ingredient;

class Ingredient implements \JsonSerializable
{
    private $id;

    private $name;

    public function __construct(string $name)
    {
        $this->name = $name;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function setId(int $id) : Ingredient
    {
        if (!is_null($this->id)) {
            throw new \DomainException('The id should not be changed once it has been set');
        }

        $this->id = $id;

        return $this;
    }

    public function getId() : ?int
    {
        return $this->id;
    }

    public function jsonSerialize()
    {
        return get_object_vars($this);
    }
}