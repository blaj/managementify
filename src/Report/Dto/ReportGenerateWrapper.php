<?php

namespace App\Report\Dto;

readonly class ReportGenerateWrapper {

  public function __construct(
      public int $userId,
      public int $companyId,
      public ReportType $reportType) {}
}