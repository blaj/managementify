<?php

namespace App\Visit\Dto;

use DateTimeImmutable;

readonly class CalendarHeaderColDto {

  public function __construct(public DateTimeImmutable $date) {}
}