<?php

namespace Tests;

use Illuminate\Contracts\Events\Dispatcher as EventsDispatcherContract;
use Laravel\BrowserKitTesting\TestCase as BaseTestCase;

abstract class BrowserKitTestCase extends BaseTestCase
{
    use CreatesApplication;

    public $baseUrl = 'http://localhost';

    /** @var \Faker\Generator */
    protected $faker;

    public function setUp(): void
    {
        parent::setUp();

        if ($this->faker === null) {
            $this->faker = \Faker\Factory::create(\App::getLocale());
        }
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
