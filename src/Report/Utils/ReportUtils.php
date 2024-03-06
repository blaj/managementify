<?php

namespace App\Report\Utils;

use App\Report\Dto\CellData;
use PhpOffice\PhpSpreadsheet\Cell\CellAddress;
use PhpOffice\PhpSpreadsheet\Cell\CellRange;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ReportUtils {

  const XLSX_DEFAULT_BORDER_STYLE = [
      'borderStyle' => Border::BORDER_THIN,
      'startColor' => ['argb' => '000000']
  ];

  const XLSX_DEFAULT_STYLE = [
      'font' => [
          'bold' => false,
          'size' => 10
      ],
      'alignment' => [
          'horizontal' => Alignment::HORIZONTAL_CENTER,
          'vertical' => Alignment::VERTICAL_CENTER,
          'wrapText' => true
      ],
      'borders' => [
          'allBorders' => self::XLSX_DEFAULT_BORDER_STYLE,
      ],
      'numberFormat' => []
  ];

  public static string $csvNewLine = PHP_EOL;
  public static string $csvSeparator = ';';

  public static function addXlsxData(Worksheet $worksheet, CellData $cellData): CellAddress {
    $columnIndex = $cellData->column;
    $rowIndex = $cellData->row;

    $cellAddress = CellAddress::fromColumnAndRow($columnIndex, $rowIndex);
    $cell = $worksheet->getCell($cellAddress);

    $cell->setValue($cellData->value);

    if ($cellData->rowMerge > 1 || $cellData->columnMerge > 1) {
      $cellRange =
          new CellRange(
              $cellAddress,
              $cellAddress =
                  CellAddress::fromColumnAndRow(
                      $columnIndex + $cellData->columnMerge - 1,
                      $rowIndex + $cellData->rowMerge - 1));

      $worksheet->mergeCells($cellRange);
    }

    $cellCoordinate = Coordinate::stringFromColumnIndex($columnIndex) . $rowIndex;
    $worksheet->getStyle($cellCoordinate)->applyFromArray($cellData->style);

    return $cellAddress->nextColumn()->nextRow();
  }
}