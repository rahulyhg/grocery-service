<?php

namespace App\Recipe;

use stdClass;
use App\Recipe\Contracts\RecipeSource;

class JsonRecipeSource implements RecipeSource
{
    private $dataDir;

    const REJECTED_FILES = [
        '.DS_Store'
    ];

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
        // bit of a hack - but this is a temp source so who cares.
        $recipe = clone $recipeToCreate;

        $id = $this->getNextId();
        $recipe->setId($id);

        file_put_contents(
            $this->dataDir . 'recipe-' . $id . '.json',
            json_encode($recipe)
        );

        return $id;
    }

    private function getNextId()
    {
        $files = $this->getDataFiles();
        $lastFile = end($files);

        $lastFile = substr($lastFile, strpos($lastFile, "-") + 1);
        $lastId = str_replace('.json', '', $lastFile);

        return ++$lastId;
    }

    private function getDataFiles()
    {
        $files = array_diff(
            scandir($this->dataDir),
            array('.', '..')
        );

        foreach ($files as $key => $file) {
            if (in_array($file, self::REJECTED_FILES)) {
                unset($files[$key]);
            }
        }

        return $files;
    }
}