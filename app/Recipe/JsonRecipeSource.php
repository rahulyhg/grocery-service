<?php

namespace App\Recipe;

use stdClass;
use App\Recipe\Contracts\RecipeSource;

class JsonRecipeSource implements RecipeSource
{
    private $dataDir;

    public function __construct()
    {
        $this->dataDir = __DIR__ . '/data/';
    }

    public function findById(int $recipeId) : ?stdClass
    {
        $file = $this->dataDir . 'recipe-' . $recipeId . '.json';

        if (!file_exists($file)) {
            return null;
        }

        return json_decode(
            file_get_contents($file)
        );
    }

    public function findAll() : array
    {
        $files = $this->getDataFiles();

        return array_map(function ($file) {
            return json_decode(
                file_get_contents($this->dataDir . $file)
            );
        }, $files);
    }

    public function persistRecipe(Recipe $recipeToCreate) : int
    {
        return 1;
    }

    private function getDataFiles()
    {
        $files = array_diff(
            scandir($this->dataDir),
            array('.', '..')
        );

        foreach ($files as $key => $file) {
            $lastChar = substr($file, -1);

            if ($lastChar === '~') {
                unset($files[$key]);
            }
        }

        return $files;
    }
}