<?php

namespace App\Visit\Dto;

readonly class VisitTypeDetailsDto {

  public function __construct(
      public int $id,
      public string $code,
      public string $name,
      public ?int $preferredPrice,
      public bool $archived) {}
}