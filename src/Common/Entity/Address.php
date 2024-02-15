<?php

namespace App\Common\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Embeddable;

#[Embeddable]
class Address {

  #[Column(name: 'city', type: Types::STRING, length: 100, nullable: false)]
  private string $city;

  #[Column(name: 'street', type: Types::STRING, length: 100, nullable: false)]
  private string $street;

  #[Column(name: 'postcode', type: Types::STRING, length: 6, nullable: false)]
  private string $postcode;

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