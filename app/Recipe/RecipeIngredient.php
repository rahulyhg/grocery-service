<?php

namespace App\Recipe;

use App\Ingredient\Ingredient;

class RecipeIngredient implements \JsonSerializable
{
    private $details;

    private $amount;

    public function __construct(Ingredient $details, string $amount)
    {
        $this->details = $details;
        $this->amount = $amount;
    }

    public function getDetails() : Ingredient
    {
        return $this->details;
    }

    public function getAmount() : string
    {
        return $this->amount;
    }

    public function jsonSerialize()
    {
        return [
            'details' => $this->getDetails(),
            'amount' => $this->getAmount()
        ];
    }
}