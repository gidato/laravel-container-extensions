<?php

namespace Gidato\Container;

use Illuminate\Foundation\Application as LaravelApplication;

class Application extends LaravelApplication
{
    use NestedContainers;
    use FactoryBinding;
}
