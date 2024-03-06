<?php

namespace App\Report\Service\Generator\VisitSummary;

use App\Common\Utils\DateTimeImmutableUtils;
use App\FileStorage\Service\FileStorageService;
use App\Report\Dto\CellData;
use App\Report\Dto\ReportGenerateWrapper;
use App\Report\Dto\ReportType;
use App\Report\Dto\VisitSummary\DataSource\VisitSummaryDataSource;
use App\Report\Service\Generator\XlsxReportGeneratorServiceInterface;
use App\Report\Utils\ReportUtils;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\File\File;

/**
 * @implements XlsxReportGeneratorServiceInterface<VisitSummaryDataSource>
 */
class VisitSummaryXlsxReportGeneratorService implements XlsxReportGeneratorServiceInterface {

  public function __construct(private readonly FileStorageService $fileStorageService) {}

  /**
   * @param VisitSummaryDataSource $dataSource
   */
  public function generate(ReportGenerateWrapper $reportGenerateWrapper, $dataSource): File {
    $reportPath = $this->fileStorageService->getReportPath($reportGenerateWrapper);
    $filePath =
        $this->fileStorageService->createFolderAndFile(
            $reportPath,
            'visit-summary-' . time() . '.xlsx');

    $spreadsheet = new Spreadsheet();
    $activeSheet = $spreadsheet->getActiveSheet();

    $rowIndex = 1;
    foreach ($dataSource->rows as $row) {
      $cellAddress =
          ReportUtils::addXlsxData($activeSheet, new CellData(1, $rowIndex, $row->specialistName));
      $cellAddress =
          ReportUtils::addXlsxData(
              $activeSheet,
              new CellData($cellAddress->columnId(), $rowIndex, $row->clientName));
      $cellAddress =
          ReportUtils::addXlsxData(
              $activeSheet,
              new CellData(
                  $cellAddress->columnId(),
                  $rowIndex,
                  $row->fromTime->format(DateTimeImmutableUtils::$dateTimeFormat)));
      $cellAddress =
          ReportUtils::addXlsxData(
              $activeSheet,
              new CellData(
                  $cellAddress->columnId(),
                  $rowIndex,
                  $row->toTime->format(DateTimeImmutableUtils::$dateTimeFormat)));
      $cellAddress =
          ReportUtils::addXlsxData(
              $activeSheet,
              new CellData($cellAddress->columnId(), $rowIndex, $row->note));
      $cellAddress =
          ReportUtils::addXlsxData(
              $activeSheet,
              new CellData($cellAddress->columnId(), $rowIndex, $row->visitTypeName));
      $cellAddress =
          ReportUtils::addXlsxData(
              $activeSheet,
              new CellData($cellAddress->columnId(), $rowIndex, (string) $row->preferredPrice));

      $rowIndex++;
    }

    $writer = new Xlsx($spreadsheet);
    $writer->save($filePath);

    return new File($filePath);
  }

  public function getReportType(): ReportType {
    return ReportType::VISIT_SUMMARY;
  }
}