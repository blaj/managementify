<?php

namespace App\Visit\Dto;

use App\Common\Dto\DateTimeImmutableRange;

readonly class CalendarDataVisitDto {

  public function __construct(public DateTimeImmutableRange $range) {}
}