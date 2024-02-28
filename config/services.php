<?php

declare(strict_types = 1);

use App\Visit\ValueResolver\VisitCellDataRequestValueResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function(ContainerConfigurator $containerConfigurator): void {
  $services = $containerConfigurator->services();

  $services->defaults()
      ->autowire()
      ->autoconfigure();

  $services->load('App\\', __DIR__ . '/../src/')
      ->exclude([
          __DIR__ . '/../src/DependencyInjection/',
          __DIR__ . '/../src/Entity/',
          __DIR__ . '/../src/Kernel.php',
      ]);

  $services->set(RequestPayloadValueResolver::class);

  $services->set(VisitCellDataRequestValueResolver::class)
      ->arg('$requestPayloadValueResolver', service(RequestPayloadValueResolver::class));
};
