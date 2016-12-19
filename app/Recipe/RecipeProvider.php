<?php

namespace App\Recipe;

use App\Recipe\Contracts\RecipeSource;
use Illuminate\Support\ServiceProvider;

class RecipeProvider extends ServiceProvider
{
    protected $defer = true;

    public function register()
    {
        $this->app->bind(
            RecipeSource::class,
            JsonRecipeSource::class
        );
    }

    public function provides()
    {
        return [
            RecipeSource::class
        ];
    }
}