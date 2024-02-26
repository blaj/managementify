<?php

namespace App\Company\Dto;

use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class CompanyUpdateRequest {

  private int $id;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $name;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $city;

  #[NotBlank]
  #[Length(min: 3, max: 100)]
  private string $street;

  #[NotBlank]
  #[Length(exactly: 6)]
  #[Regex(pattern: '^[0-9]{2}-[0-9]{3}$^')]
  private string $postcode;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

    return $this;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getCity(): string {
    return $this->city;
  }

  public function setCity(string $city): self {
    $this->city = $city;

    return $this;
  }

  public function getStreet(): string {
    return $this->street;
  }

  public function setStreet(string $street): self {
    $this->street = $street;

    return $this;
  }

  public function getPostcode(): string {
    return $this->postcode;
  }

  public function setPostcode(string $postcode): self {
    $this->postcode = $postcode;

    return $this;
  }
}