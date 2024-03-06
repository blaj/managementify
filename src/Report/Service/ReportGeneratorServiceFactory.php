<?php

namespace App\Report\Service;

use App\Common\Exception\NotYetImplementedException;
use App\Report\Dto\ReportFileType;
use App\Report\Service\Generator\ReportGeneratorServiceInterface;
use JsonSerializable;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;

/**
 * @template DS of JsonSerializable
 */
class ReportGeneratorServiceFactory {

  /**
   * @var array<string, ReportGeneratorServiceInterface<DS>>
   */
  private array $reportGeneratorServicesMap = [];

  /**
   * @param iterable<ReportGeneratorServiceInterface<DS>> $reportGeneratorServices
   */
  public function __construct(#[TaggedIterator('app.report-generator-service')] iterable $reportGeneratorServices) {
    $this->fillReportGeneratorServicesMap(iterator_to_array($reportGeneratorServices));
  }

  /**
   * @return ReportGeneratorServiceInterface<DS>
   */
  public function getInstance(ReportFileType $reportFileType): ReportGeneratorServiceInterface {
    return $this->reportGeneratorServicesMap[$reportFileType->value]
        ??
        throw new NotYetImplementedException();
  }

  /**
   * @param array<ReportGeneratorServiceInterface<DS>> $reportGeneratorServices
   */
  private function fillReportGeneratorServicesMap(array $reportGeneratorServices): void {
    foreach ($reportGeneratorServices as $reportGeneratorService) {
      $this->reportGeneratorServicesMap[$reportGeneratorService->getReportFileType()->value] =
          $reportGeneratorService;
    }
  }
}