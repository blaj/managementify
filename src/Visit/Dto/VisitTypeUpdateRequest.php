<?php

namespace App\Visit\Dto;

use App\Common\Dto\CodeInterface;
use App\Common\Dto\CompanyIdInterface;
use App\Common\Dto\IdInterface;
use App\Common\Validator\CodeIsNotTaken;
use App\Visit\Entity\VisitType;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\PositiveOrZero;

#[CodeIsNotTaken(entityClass: VisitType::class, isCreate: false)]
class VisitTypeUpdateRequest implements IdInterface, CodeInterface, CompanyIdInterface {

  private int $id;

  #[NotBlank]
  #[Length(min: 3, max: 50)]
  private string $code;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $name;

  #[PositiveOrZero]
  private ?int $preferredPrice;

  private int $companyId;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

    return $this;
  }

  public function getCode(): string {
    return $this->code;
  }

  public function setCode(string $code): self {
    $this->code = $code;

    return $this;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getPreferredPrice(): ?int {
    return $this->preferredPrice;
  }

  public function setPreferredPrice(?int $preferredPrice): self {
    $this->preferredPrice = $preferredPrice;

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