<?php

namespace App\Client\Dto;

use App\Client\Validator\PreferredHourNotExistsInRange;
use App\Common\Entity\DayOfWeek;
use DateTimeImmutable;

#[PreferredHourNotExistsInRange(isCreate: true)]
class PreferredHourCreateRequest {

  private int $clientId;

  private DateTimeImmutable $fromTime;

  private DateTimeImmutable $toTime;

  /**
   * @var array<DayOfWeek>
   */
  private array $dayOfWeeks = [];

  private int $companyId;

  public function getClientId(): int {
    return $this->clientId;
  }

  public function setClientId(int $clientId): self {
    $this->clientId = $clientId;

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

  /**
   * @return array<DayOfWeek>
   */
  public function getDayOfWeeks(): array {
    return $this->dayOfWeeks;
  }

  /**
   * @param array<DayOfWeek> $dayOfWeeks
   */
  public function setDayOfWeeks(array $dayOfWeeks): self {
    $this->dayOfWeeks = $dayOfWeeks;

    return $this;
  }

  public function getCompanyId(): int {
    return $this->companyId;
  }

  public function setCompanyId(int $companyId): self {
    $this->companyId = $companyId;

    return $this;
  }
}