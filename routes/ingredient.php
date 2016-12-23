<?php

$app->get(
    '/ingredients',
    'IngredientController@index'
);

$app->get(
    '/ingredients/{id}',
    'IngredientController@show'
);
