<?php

namespace Tests;

class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    /** @var \Faker\Generator */
    protected $faker;

    /** @var callable[] */
    protected $bootingCallbacks = [];

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
        foreach ($this->bootingCallbacks as $callback) {
            $app->booting($callback);
        }
        $kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
        $kernel->bootstrap();
        $this->baseUrl = env('TEST_HOST', 'http://localhost');

        return $app;
    }

    /**
     * @param string $type
     * @param string|null $message
     * @param callable $function
     */
    protected function assertException($type, $message, callable $function)
    {
        $exception = null;

        try {
            call_user_func($function);
        } catch (\Exception $e) {
            $exception = $e;
        }

        self::assertThat($exception, new \PHPUnit_Framework_Constraint_Exception($type));

        if ($message !== null) {
            self::assertThat($exception, new \PHPUnit_Framework_Constraint_ExceptionMessage($message));
        }
    }
}
