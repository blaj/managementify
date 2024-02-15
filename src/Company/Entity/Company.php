<?php

namespace App\Company\Entity;

use App\Common\Entity\Address;
use App\Common\Entity\SoftDeleteEntity;
use App\Company\Repository\CompanyRepository;
use Doctrine\ORM\Mapping\Embedded;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: CompanyRepository::class)]
#[Table(name: 'company', schema: 'company')]
class Company extends SoftDeleteEntity {

  private string $name;

  #[Embedded(class: Address::class, columnPrefix: false)]
  private Address $address;

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getAddress(): Address {
    return $this->address;
  }

  public function setAddress(Address $address): self {
    $this->address = $address;

    return $this;
  }
}