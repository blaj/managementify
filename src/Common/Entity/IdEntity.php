<?php

namespace App\Common\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
class IdEntity {

  #[Id]
  #[GeneratedValue(strategy: 'SEQUENCE')]
  #[Column(name: 'id', type: Types::BIGINT)]
  protected int $id;

  public function getId(): int {
    return $this->id;
  }

  public function setId(int $id): self {
    $this->id = $id;

    return $this;
  }
}