<?php

declare(strict_types = 1);

use App\Report\Service\Generator\CsvReportGeneratorServiceInterface;
use App\Report\Service\Generator\ReportGeneratorServiceInterface;
use App\Report\Service\Generator\XlsxReportGeneratorServiceInterface;
use App\Report\Service\ReportGeneratorServiceFactory;
use App\Visit\ValueResolver\VisitCellDataRequestValueResolver;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver\RequestPayloadValueResolver;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function(ContainerConfigurator $containerConfigurator): void {
  $services = $containerConfigurator->services();

  $services->defaults()
      ->autowire()
      ->autoconfigure();

  $services
      ->instanceof(ReportGeneratorServiceInterface::class)
      ->tag('app.report-generator-service')
      ->lazy(false);

  $services
      ->instanceof(CsvReportGeneratorServiceInterface::class)
      ->tag('app.csv-report-generator-service')
      ->lazy(false);

  $services
      ->instanceof(XlsxReportGeneratorServiceInterface::class)
      ->tag('app.xlsx-report-generator-service')
      ->lazy(false);

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
