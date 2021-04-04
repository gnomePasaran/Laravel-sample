<?php

namespace Tests;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use RonasIT\Support\AutoDoc\Tests\AutoDocTestCase;

abstract class TestCase extends AutoDocTestCase
{
    use CreatesApplication;
    use DatabaseMigrations;
}
