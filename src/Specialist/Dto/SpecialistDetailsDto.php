<?php

namespace App\Specialist\Dto;

readonly class SpecialistDetailsDto {

  public function __construct(
      public int $id,
      public string $firstname,
      public string $surname,
      public ?string $foreignId) {}
}