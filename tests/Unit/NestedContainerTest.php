<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use stdClass;
use Illuminate\Container\EntryNotFoundException;

class NestedContainerTest extends TestCase
{
    /** @test */
    public function create_container_and_bind_something_to_it()
    {
        $this->app->container('c1')->bind('test1', stdClass::class);
        $this->app->container('c1')->container('c2')->bind('test2', stdClass::class);

        $this->assertFalse($this->app->has('test1'));
        $this->assertTrue($this->app->container('c1')->has('test1'));

        $this->assertFalse($this->app->has('test2'));
        $this->assertFalse($this->app->container('c1')->has('test2'));
        $this->assertTrue($this->app->container('c1')->container('c2')->has('test2'));

        $this->assertCount(1, $this->app->container('c1')->getAllBound());
        $this->assertCount(2, $this->app->container('c1')->getAllBound($includeContainers = true));
    }

    /** @test */
    public function can_access_an_object_in_an_owning_container()
    {
        $this->app->container('c1')->bind('test1', stdClass::class);
        $this->app->container('c1')->container('c2')->bind('test2', stdClass::class);
        $this->assertEquals(stdClass::class, get_class($this->app->container('c1')->container('c2')->get('test1')));
    }

    /** @test */
    public function cannot_access_an_object_in_a_child_container()
    {
        $this->app->container('c1')->bind('test1', stdClass::class);
        $this->app->container('c1')->container('c2')->bind('test2', stdClass::class);

        $this->expectException(EntryNotFoundException::class);
        $this->expectExceptionMessage('test2');
        $this->app->container('c1')->get('test2');
    }

}
