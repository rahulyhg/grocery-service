<?php

$app->get(
    '/ingredients',
    'IngredientController@index'
);

$app->post(
    '/ingredients',
    'IngredientController@store'
);

$app->get(
    '/ingredients/{id}',
    'IngredientController@show'
);
