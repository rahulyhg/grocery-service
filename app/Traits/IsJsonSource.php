<?php

namespace App\Traits;

trait IsJsonSource
{
    protected $dataDir;

    protected $rejectedFiles = [
        '.DS_Store'
    ];

    protected function getNextId()
    {
        $files = $this->getDataFiles();
        $lastFile = end($files);

        $lastFile = substr($lastFile, strpos($lastFile, "-") + 1);
        $lastId = str_replace('.json', '', $lastFile);

        return ++$lastId;
    }

    protected function getDataFiles()
    {
        $files = array_diff(
            scandir($this->dataDir),
            array('.', '..')
        );

        foreach ($files as $key => $file) {
            if (in_array($file, $this->rejectedFiles)) {
                unset($files[$key]);
            }
        }

        return $files;
    }
}