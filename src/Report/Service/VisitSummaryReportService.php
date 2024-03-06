<?php

namespace App\Report\Service;

use App\Report\Dto\ReportGenerateWrapper;
use App\Report\Dto\ReportType;
use App\Report\Dto\VisitSummary\DataSource\VisitSummaryDataSource;
use App\Report\Dto\VisitSummary\Request\VisitSummaryGenerateRequest;
use App\Report\Service\DataSource\VisitSummaryReportDataSourceProviderService;
use Symfony\Component\HttpFoundation\File\File;

class VisitSummaryReportService {

  /**
   * @param ReportGeneratorServiceFactory<VisitSummaryDataSource> $reportGeneratorServiceFactory
   */
  public function __construct(
      private readonly VisitSummaryReportDataSourceProviderService $visitSummaryReportDataSourceProviderService,
      private readonly ReportGeneratorServiceFactory $reportGeneratorServiceFactory) {}

  public function generate(
      VisitSummaryGenerateRequest $visitSummaryGenerateRequest,
      int $userId,
      int $companyId): File {
    $generator =
        $this->reportGeneratorServiceFactory->getInstance(
            $visitSummaryGenerateRequest->getReportFileType());

    return $generator->generate(
        new ReportGenerateWrapper($userId, $companyId, ReportType::VISIT_SUMMARY),
        $this->visitSummaryReportDataSourceProviderService->getDataSource($companyId));
  }
}