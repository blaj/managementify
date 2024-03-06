<?php

namespace App\Report\Dto;

use App\Report\Utils\ReportUtils;

readonly class CellData {

  /**
   * @param array<string, array<string, array<string, array<string, string>|string>|bool|int|string>> $style
   */
  public function __construct(
      public int $column,
      public int $row,
      public ?string $value,
      public int $columnMerge = 1,
      public int $rowMerge = 1,
      public array $style = ReportUtils::XLSX_DEFAULT_STYLE) {}
}