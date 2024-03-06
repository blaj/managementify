<?php

namespace App\Report\Dto\VisitSummary\DataSource;

use JsonSerializable;

readonly class VisitSummaryDataSource implements JsonSerializable {

  /**
   * @param array<VisitSummaryRow> $rows
   */
  public function __construct(public array $rows = []) {}

  /**
   * @return array<string, array<VisitSummaryRow>>
   */
  public function jsonSerialize(): array {
    return [
        'rows' => $this->rows
    ];
  }
}