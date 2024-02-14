<?php

namespace App\Visit\Dto;

readonly class CalendarRowDto {

  /**
   * @param array<CalendarDataColDto> $dataCols
   */
  public function __construct(public CalendarInfoColDto $infoCol, public array $dataCols) {}
}