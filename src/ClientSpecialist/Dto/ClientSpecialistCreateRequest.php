<?php

namespace App\ClientSpecialist\Dto;

use App\ClientSpecialist\Entity\AssignType;
use App\ClientSpecialist\Validator\MainAssignTypeIsNotExists;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

#[MainAssignTypeIsNotExists(isCreate: true)]
class ClientSpecialistCreateRequest {

  private ?DateTimeImmutable $fromDate = null;

  #[GreaterThanOrEqual(propertyPath: 'fromDate')]
  private ?DateTimeImmutable $toDate = null;

  private AssignType $assignType;

  private int $clientId;

  private int $specialistId;

  private int $companyId;

  public function getFromDate(): ?DateTimeImmutable {
    return $this->fromDate;
  }

  public function setFromDate(?DateTimeImmutable $fromDate): self {
    $this->fromDate = $fromDate;

    return $this;
  }

  public function getToDate(): ?DateTimeImmutable {
    return $this->toDate;
  }

  public function setToDate(?DateTimeImmutable $toDate): self {
    $this->toDate = $toDate;

    return $this;
  }

  public function getAssignType(): AssignType {
    return $this->assignType;
  }

  public function setAssignType(AssignType $assignType): self {
    $this->assignType = $assignType;

    return $this;
  }

  public function getClientId(): int {
    return $this->clientId;
  }

  public function setClientId(int $clientId): self {
    $this->clientId = $clientId;

    return $this;
  }

  public function getSpecialistId(): int {
    return $this->specialistId;
  }

  public function setSpecialistId(int $specialistId): self {
    $this->specialistId = $specialistId;

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