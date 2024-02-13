<?php

namespace App\Client\Dto;

class ClientCreateRequest {

  private string $firstname;

  private string $surname;

  private ?string $foreignId = null;

  public function getFirstname(): string {
    return $this->firstname;
  }

  public function setFirstname(string $firstname): self {
    $this->firstname = $firstname;

    return $this;
  }

  public function getSurname(): string {
    return $this->surname;
  }

  public function setSurname(string $surname): self {
    $this->surname = $surname;

    return $this;
  }

  public function getForeignId(): ?string {
    return $this->foreignId;
  }

  public function setForeignId(?string $foreignId): self {
    $this->foreignId = $foreignId;

    return $this;
  }
}