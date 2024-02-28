<?php

namespace App\Visit\Dto;

use DateTimeImmutable;

readonly class CalendarDataColDto {

  /**
   * @param array<CalendarDataVisitDto> $visits
   */
  public function __construct(public array $visits, public DateTimeImmutable $date) {}
}