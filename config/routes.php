<?php

declare(strict_types=1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routingConfigurator): void {
    $routingConfigurator->import([
        'path' => '../src/Dashboard/Controller/',
        'namespace' => 'App\Dashboard\Controller',
    ], 'attribute');
};
