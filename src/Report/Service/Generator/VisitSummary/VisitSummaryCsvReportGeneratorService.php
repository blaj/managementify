<?php

namespace App\Report\Service\Generator\VisitSummary;

use App\Common\Utils\DateTimeImmutableUtils;
use App\FileStorage\Service\FileStorageService;
use App\Report\Dto\ReportGenerateWrapper;
use App\Report\Dto\ReportType;
use App\Report\Dto\VisitSummary\DataSource\VisitSummaryDataSource;
use App\Report\Dto\VisitSummary\DataSource\VisitSummaryRow;
use App\Report\Service\Generator\CsvReportGeneratorServiceInterface;
use App\Report\Utils\ReportUtils;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @implements CsvReportGeneratorServiceInterface<VisitSummaryDataSource>
 */
class VisitSummaryCsvReportGeneratorService implements CsvReportGeneratorServiceInterface {

  public function __construct(private readonly FileStorageService $fileStorageService) {}

  /**
   * @param VisitSummaryDataSource $dataSource
   */
  public function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File {
    $reportPath = $this->fileStorageService->getReportPath($reportGenerateWrapper);

    $content =
        implode(
            ReportUtils::$csvNewLine,
            array_map(fn (VisitSummaryRow $row) => self::mapRowToString($row), $dataSource->rows));

    $filePath =
        $this->fileStorageService->saveContentToFile(
            $content,
            'visit-summary-' . time() . '.csv',
            $reportPath);

    return new File($filePath);
  }

  public function getReportType(): ReportType {
    return ReportType::VISIT_SUMMARY;
  }

  private static function mapRowToString(VisitSummaryRow $row): string {
    return $row->specialistName
        . ReportUtils::$csvSeparator
        . $row->clientName
        . ReportUtils::$csvSeparator
        . $row->fromTime->format(DateTimeImmutableUtils::$dateTimeFormat)
        . ReportUtils::$csvSeparator
        . $row->toTime->format(DateTimeImmutableUtils::$dateTimeFormat)
        . ReportUtils::$csvSeparator
        . $row->note
        . ReportUtils::$csvSeparator
        . $row->visitTypeName
        . ReportUtils::$csvSeparator
        . $row->preferredPrice;
  }
}