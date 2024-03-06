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
class XlsxReportGeneratorService implements ReportGeneratorServiceInterface {

  /**
   * @var array<string, XlsxReportGeneratorServiceInterface<DS>>
   */
  private array $xlsxReportGeneratorServicesMap = [];

  /**
   * @param iterable<XlsxReportGeneratorServiceInterface<DS>> $xlsxReportGeneratorServices
   */
  public function __construct(
      #[TaggedIterator('app.xlsx-report-generator-service')] iterable $xlsxReportGeneratorServices) {
    $this->fillXlsxReportGeneratorServicesMap(iterator_to_array($xlsxReportGeneratorServices));
  }

  /**
   * @param DS $dataSource
   */
  public function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File {
    $generator =
        $this->xlsxReportGeneratorServicesMap[$reportGenerateWrapper->reportType->value]
        ??
        throw new NotYetImplementedException();

    return $generator->generate($reportGenerateWrapper, $dataSource);
  }

  public function getReportFileType(): ReportFileType {
    return ReportFileType::XLSX;
  }

  /**
   * @param array<XlsxReportGeneratorServiceInterface<DS>> $xlsxReportGeneratorServices
   */
  private function fillXlsxReportGeneratorServicesMap(array $xlsxReportGeneratorServices): void {
    foreach ($xlsxReportGeneratorServices as $xlsxReportGeneratorService) {
      $this->xlsxReportGeneratorServicesMap[$xlsxReportGeneratorService->getReportType()->value] =
          $xlsxReportGeneratorService;
    }
  }
}