<?php

namespace App\Client\Dto;

use App\Client\Entity\ContactType;

class ContactUpdateRequest {

  private int $id;

  private string $content;

  private ContactType $type;

  private int $clientId;

  private int $companyId;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

    return $this;
  }

  public function getContent(): string {
    return $this->content;
  }

  public function setContent(string $content): self {
    $this->content = $content;

    return $this;
  }

  public function getType(): ContactType {
    return $this->type;
  }

  public function setType(ContactType $type): self {
    $this->type = $type;

    return $this;
  }

  public function getClientId(): int {
    return $this->clientId;
  }

  public function setClientId(int $clientId): self {
    $this->clientId = $clientId;

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