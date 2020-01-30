<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use Psr\Container\ContainerInterface;
use Gidato\Container\Contract\FactoryContract;

class FactoryTest extends TestCase
{

    /** @test */
    public function singleton_using_factory_and_retreive()
    {
        $this->app->singletonFromFactory('test1', Factory1::class);
        $test1 = $this->app->get('test1');
        $test2 = $this->app->get('test1');

        $this->assertTrue($test1 === $test2);

        $this->assertEquals('xxx', $test1->prop);
    }

    /** @test */
    public function bind_using_factory_and_retreive()
    {
        $this->app->bindFromFactory('test2', Factory1::class); // not shared
        $test1 = $this->app->get('test2');
        $test2 = $this->app->get('test2');

        $this->assertFalse($test1 === $test2);
        $this->assertTrue($test1 == $test2);

        $this->assertEquals('xxx', $test1->prop);
    }

}

class Factory1 implements FactoryContract
{
    public function __invoke(ContainerInterface $container, string $requestedName, array $parameters = [])
    {
        return (object) ['prop' => 'xxx'];
    }
}
