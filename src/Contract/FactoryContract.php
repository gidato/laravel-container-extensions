<?php

namespace Gidato\Container\Contract;

use Psr\Container\ContainerInterface;

interface FactoryContract
{
    public function __invoke(ContainerInterface $container, string $requestedName, array $parameters = []);
}
