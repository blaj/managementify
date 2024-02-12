<?php

namespace App\Common\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
class SoftDeleteEntity extends IdEntity {

  #[Column(name: 'deleted', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  protected bool $deleted = false;

  public function isDeleted(): bool {
    return $this->deleted;
  }

  public function setDeleted(bool $deleted): self {
    $this->deleted = $deleted;

    return $this;
  }
}