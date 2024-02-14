<?php

namespace App\Visit\Dto;

readonly class CalendarDto {

  /**
   * @param array<CalendarHeaderColDto> $headerCols
   * @param array<CalendarRowDto> $rows
   */
  public function __construct(public array $headerCols, public array $rows) {}
}