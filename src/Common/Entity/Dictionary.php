<?php

namespace App\Common\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\MappedSuperclass;

#[MappedSuperclass]
class Dictionary extends IdEntity {

  #[Column(name: 'archived', type: Types::BOOLEAN, nullable: false, options: ['default' => false])]
  protected bool $archived = false;

  #[Column(name: 'name', type: Types::STRING, length: 100, nullable: false)]
  protected string $name;

  #[Column(name: 'code', type: Types::STRING, length: 50, nullable: false)]
  protected string $code;

  public function isArchived(): bool {
    return $this->archived;
  }

  public function setArchived(bool $archived): self {
    $this->archived = $archived;

    return $this;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  public function getCode(): string {
    return $this->code;
  }

  public function setCode(string $code): self {
    $this->code = $code;

    return $this;
  }
}