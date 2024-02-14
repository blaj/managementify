<?php

namespace App\Visit\Dto;

readonly class CalendarInfoColDto {

  public function __construct(
      public string $specialistFirstname,
      public string $specialistSurname) {}

}