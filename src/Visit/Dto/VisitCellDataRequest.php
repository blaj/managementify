<?php

namespace App\Visit\Dto;

use DateTimeImmutable;

class VisitCellDataRequest {

  private DateTimeImmutable $date;

  private int $specialistId;

  public function getDate(): DateTimeImmutable {
    return $this->date;
  }

  public function setDate(DateTimeImmutable $date): self {
    $this->date = $date;

    return $this;
  }

  public function getSpecialistId(): int {
    return $this->specialistId;
  }

  public function setSpecialistId(int $specialistId): self {
    $this->specialistId = $specialistId;

    return $this;
  }
}