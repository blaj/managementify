<?php

namespace App\Report\Service\Generator;

use App\Common\Exception\NotYetImplementedException;
use App\Report\Dto\ReportFileType;
use App\Report\Dto\ReportGenerateWrapper;
use JsonSerializable;
use Symfony\Component\DependencyInjection\Attribute\TaggedIterator;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @template DS of JsonSerializable
 * @implements ReportGeneratorServiceInterface<DS>
 */
class CsvReportGeneratorService implements ReportGeneratorServiceInterface {

  /**
   * @var array<string, CsvReportGeneratorServiceInterface<DS>>
   */
  private array $csvReportGeneratorServicesMap = [];

  /**
   * @param iterable<CsvReportGeneratorServiceInterface<DS>> $csvReportGeneratorServices
   */
  public function __construct(
      #[TaggedIterator('app.csv-report-generator-service')] iterable $csvReportGeneratorServices) {
    $this->fillCsvReportGeneratorServicesMap(iterator_to_array($csvReportGeneratorServices));
  }

  /**
   * @param DS $dataSource
   */
  public function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File {
    $generator =
        $this->csvReportGeneratorServicesMap[$reportGenerateWrapper->reportType->value]
        ??
        throw new NotYetImplementedException();

    return $generator->generate($reportGenerateWrapper, $dataSource);
  }

  public function getReportFileType(): ReportFileType {
    return ReportFileType::CSV;
  }

  /**
   * @param array<CsvReportGeneratorServiceInterface<DS>> $csvReportGeneratorServices
   */
  private function fillCsvReportGeneratorServicesMap(array $csvReportGeneratorServices): void {
    foreach ($csvReportGeneratorServices as $csvReportGeneratorService) {
      $this->csvReportGeneratorServicesMap[$csvReportGeneratorService->getReportType()->value] =
          $csvReportGeneratorService;
    }
  }
}