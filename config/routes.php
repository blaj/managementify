<?php

declare(strict_types = 1);

use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function(RoutingConfigurator $routingConfigurator): void {
  $routingConfigurator->import([
      'path' => '../src/Home/Controller/',
      'namespace' => 'App\Home\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Dashboard/Controller/',
      'namespace' => 'App\Dashboard\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Specialist/Controller/',
      'namespace' => 'App\Specialist\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Client/Controller/',
      'namespace' => 'App\Client\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Visit/Controller/',
      'namespace' => 'App\Visit\Controller',
  ], 'attribute');

  $routingConfigurator->import([
      'path' => '../src/Security/Controller/',
      'namespace' => 'App\Security\Controller',
  ], 'attribute');
};
