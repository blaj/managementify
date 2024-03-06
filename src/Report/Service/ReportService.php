<?php

namespace App\Report\Service;

use App\Report\Dto\ReportType;

class ReportService {

  /**
   * @return array<ReportType>
   */
  public function getList(): array {
    return ReportType::cases();
  }
}