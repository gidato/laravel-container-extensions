# Gidato / Laravel-Container-Extensions

```

composer require gidato/laravel-container-extensions

```

You then need to find the following line in \\bootstrap\\app.php

```
$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);
```
And change it as follows:

```
$app = new Gidato\Container\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);
```

#### Nested Containers

A separate container can be created and used within a container, effectively namespacing the bound elements.

This allows specific parts of the application to be directly associated with a specific container.

To access sub containers, you can use `app()->container("name-of-container")`, and then bind things to this level.

Directly bound classes can then be retrieved (if you want them all), using `$container->getAllBound()`. This will give only specifically bound classes/objects, and will not deliver any that could be dynamically created.

If you want to exclude other nested containers from this call, use `$container->getAllBound($recursive = true)`

#### Binding using Factory Creation

Instead of creating a closure in the Provider, this allows the creation to be done within a factory class, which can then live with the class, making it more accessible.

The factory must implement `Gidato\Container\Contract\FactoryContract` which requires that the class be invokable.  The method should be defined to match the following:

```
public function __invoke(\Psr\Container\ContainerInterface $container, string $requestedName, array $parameters = []);
```

And then, in the provider, class can be bound using:

```
$app->singletonFromFactory($abstract, $factoryClass)
$app->bindFromFactory($abstract, $factoryClass, $shared = false)
```

## License

This software is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
