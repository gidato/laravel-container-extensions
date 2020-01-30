<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    public function createApplication()
    {
        return new \Gidato\Container\Application(dirname(dirname(__DIR__)));
    }
}
