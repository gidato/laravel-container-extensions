<?php

namespace Gidato\Container;

use Illuminate\Container\Container as LaravelContainer;

class Container extends LaravelContainer
{
    use NestedContainers;
    use FactoryBinding;
}
