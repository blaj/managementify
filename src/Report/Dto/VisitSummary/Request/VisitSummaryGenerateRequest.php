<?php

namespace App\Report\Dto\VisitSummary\Request;

use App\Report\Dto\ReportFileType;

class VisitSummaryGenerateRequest {

  private ReportFileType $reportFileType;

  public function getReportFileType(): ReportFileType {
    return $this->reportFileType;
  }

  public function setReportFileType(ReportFileType $reportFileType): self {
    $this->reportFileType = $reportFileType;

    return $this;
  }
}