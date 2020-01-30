<?php

namespace Gidato\Container;

use Illuminate\Container\Container as LaravelContainer;

trait NestedContainers
{
    private $containerPrefix = 'gidato_container:';

    private $parent;

    public function container(string $name) : Container
    {
        if (!$this->has($this->containerPrefix . $name)) {
            $this->createNewContainerForName($this->containerPrefix . $name);
        }
        return $this->get($this->containerPrefix . $name);
    }

    private function createNewContainerForName(string $fullName) : void
    {
        $container = new Container();
        $container->setParent($this);
        $this->instance($fullName, $container);
    }

    /**
     * overwrite standard to check if directly bound in this container
     * if it is not, and we have a parent container, then ask it to make the object
     * otherwise use the inherited make function to make the object
     */
    public function make($abstract, array $parameters = [])
    {
        if (!$this->has($abstract) && $this->hasParent()) {
            return $this->getParent()->make($abstract, $parameters);
        }
        return parent::make($abstract, $parameters);
    }

    /**
     * overwrite standard to check if directly bound in this container
     * if it is not, and we have a parent container, then ask it to get the object
     * otherwise use the inherited get function to get the object
     */
    public function get($id)
    {
        if (!$this->has($id) && $this->hasParent()) {
            return $this->getParent()->get($id);
        }
        return parent::get($id);
    }

    public function setParent(LaravelContainer $container) : void
    {
        $this->parent = $container;
    }

    private function hasParent() : bool
    {
        return !empty($this->parent);
    }

    private function getParent() : LaravelContainer
    {
        return $this->parent;
    }

    public function getAllBound(bool $includeContainers = false) : array
    {
        $bindings = collect(array_keys($this->bindings))
            ->merge(array_keys($this->instances))
            ->merge(array_keys($this->aliases))
            ->unique();

        if (!$includeContainers) {
            $bindings = $bindings->reject(function ($key) {
                return strpos($key, $this->containerPrefix) === 0;
            });
        }

        return $bindings->mapWithKeys(function ($key) {
                return [$key => $this->get($key)];
            })
            ->toArray();
    }

}
