<?php

namespace App\Ingredient;

use Illuminate\Support\ServiceProvider;
use App\Ingredient\Contracts\IngredientSource;

class IngredientProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(
            IngredientSource::class,
            JsonIngredientSource::class
        );
    }

    public function provides()
    {
        return [
            IngredientSource::class
        ];
    }
}