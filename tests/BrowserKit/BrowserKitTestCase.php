<?php

namespace Tests\BrowserKit;

use Elasticsearch\Client;
use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;
use Laravel\BrowserKitTesting\TestCase;

abstract class BrowserKitTestCase extends TestCase
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
        $app = require __DIR__ . '/../../bootstrap/app.php';
        $app->loadEnvironmentFrom('.env.testing');
        $app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();
        $app->instance(Client::class, $this->createMock(Client::class));

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

    /**
     * Fix \Laravel\BrowserKitTesting\TestCase::withoutEvents
     * @inheritDoc
     */
    protected function withoutEvents()
    {
        $mock = \Mockery::mock(EventsDispatcherContract::class)->shouldIgnoreMissing();

        $mock->shouldReceive('dispatch')->andReturnUsing(function ($called) {
            $this->firedEvents[] = $called;
        });

        $this->app->instance('events', $mock);

        return $this;
    }
}
