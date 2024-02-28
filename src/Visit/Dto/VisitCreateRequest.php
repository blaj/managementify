<?php

namespace App\Visit\Dto;

use DateTimeImmutable;

class VisitCreateRequest {

  private DateTimeImmutable $date;

  private DateTimeImmutable $fromTime;

  private DateTimeImmutable $toTime;

  private int $specialistId;

  private int $clientId;

  private ?string $note = null;

  public function getDate(): DateTimeImmutable {
    return $this->date;
  }

  public function setDate(DateTimeImmutable $date): self {
    $this->date = $date;

    return $this;
  }

  public function getFromTime(): DateTimeImmutable {
    return $this->fromTime;
  }

  public function setFromTime(DateTimeImmutable $fromTime): self {
    $this->fromTime = $fromTime;

    return $this;
  }

  public function getToTime(): DateTimeImmutable {
    return $this->toTime;
  }

  public function setToTime(DateTimeImmutable $toTime): self {
    $this->toTime = $toTime;

    return $this;
  }

  public function getSpecialistId(): int {
    return $this->specialistId;
  }

  public function setSpecialistId(int $specialistId): self {
    $this->specialistId = $specialistId;

    return $this;
  }

  public function getClientId(): int {
    return $this->clientId;
  }

  public function setClientId(int $clientId): self {
    $this->clientId = $clientId;

    return $this;
  }

  public function getNote(): ?string {
    return $this->note;
  }

  public function setNote(?string $note): self {
    $this->note = $note;

    return $this;
  }
}