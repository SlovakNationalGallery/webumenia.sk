<?php

namespace Tests;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /** @var \Faker\Generator */
    protected $faker;

    public function setUp() {
        parent::setUp();
        if ($this->faker === null) {
            $this->faker = \Faker\Factory::create(\App::getLocale());
        }
    }

    /**
     * Creates the application.
     *
     * @return \Symfony\Component\HttpKernel\HttpKernelInterface
     */
    public function createApplication()
    {
        /** @var \Illuminate\Foundation\Application $app */
        $app = require __DIR__ . '/../bootstrap/app.php';
        $app->loadEnvironmentFrom('.env.testing');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $this->baseUrl = env('TEST_HOST', 'http://localhost');

        return $app;
    }
}
