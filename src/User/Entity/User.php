<?php

namespace App\User\Entity;

use App\Common\Entity\SoftDeleteEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'users', schema: 'users')]
class User extends SoftDeleteEntity {

  #[Column(name: 'username', type: Types::STRING, length: 50, nullable: false)]
  private string $username;

  #[Column(name: 'password', type: Types::STRING, length: 200, nullable: false)]
  private string $password;

  public function getUsername(): string {
    return $this->username;
  }

  public function setUsername(string $username): self {
    $this->username = $username;

    return $this;
  }

  public function getPassword(): string {
    return $this->password;
  }

  public function setPassword(string $password): self {
    $this->password = $password;

    return $this;
  }
}