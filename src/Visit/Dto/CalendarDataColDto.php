<?php

namespace App\Visit\Dto;

readonly class CalendarDataColDto {

  /**
   * @param array<CalendarDataVisitDto> $visits
   */
  public function __construct(public array $visits) {}
}