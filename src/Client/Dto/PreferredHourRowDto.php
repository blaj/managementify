<?php

namespace App\Client\Dto;

use DateTimeImmutable;

class PreferredHourRowDto {

  private int $id;

  private DateTimeImmutable $fromTime;

  private DateTimeImmutable $toTime;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

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
}