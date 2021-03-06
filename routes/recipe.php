<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$app->get(
    '/recipes',
    'RecipeController@index'
);

$app->get(
    '/recipes/{id}',
    'RecipeController@show'
);

$app->put(
    '/recipes/{id}/ingredients',
    'RecipeController@addIngredient'
);

$app->post(
    '/recipes',
    'RecipeController@store'
);