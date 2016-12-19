<?php

class TestCase extends Laravel\Lumen\Testing\TestCase
{
    /**
     * Creates the application.
     *
     * @return \Laravel\Lumen\Application
     */
    public function createApplication()
    {
        return require __DIR__.'/../bootstrap/app.php';
    }

    protected function getFixture($file)
    {
        $fileName = __DIR__ . '/Acceptance/fixtures/' . $file . '.json';

        if (!file_exists($fileName)) {
            $this->fail("Fixture $file could not be found");
        }

        $contents = file_get_contents($fileName);

        return json_decode($contents);
    }
}
