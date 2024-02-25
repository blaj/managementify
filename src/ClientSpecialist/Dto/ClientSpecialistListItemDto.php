<?php

namespace App\ClientSpecialist\Dto;

use App\ClientSpecialist\Entity\AssignType;
use DateTimeImmutable;

readonly class ClientSpecialistListItemDto {

  public function __construct(
      public int $id,
      public string $clientName,
      public string $specialistName,
      public ?DateTimeImmutable $fromDate,
      public ?DateTimeImmutable $toDate,
      public AssignType $assignType) {}
}