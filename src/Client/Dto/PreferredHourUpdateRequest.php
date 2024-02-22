<?php

namespace App\Client\Dto;

use App\Client\Validator\PreferredHourNotExistsInRange;
use DateTimeImmutable;

#[PreferredHourNotExistsInRange(isCreate: false)]
class PreferredHourUpdateRequest {

  private int $id;

  private int $clientId;

  private DateTimeImmutable $fromTime;

  private DateTimeImmutable $toTime;

  private int $companyId;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

    return $this;
  }

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

  public function getCompanyId(): int {
    return $this->companyId;
  }

  public function setCompanyId(int $companyId): self {
    $this->companyId = $companyId;

    return $this;
  }
}