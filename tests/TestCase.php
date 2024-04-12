<?php

namespace Tests;

use Illuminate\Foundation\Testing\WithFaker;

abstract class TestCase extends \Illuminate\Foundation\Testing\TestCase
{
    use CreatesApplication, WithFaker;
}
