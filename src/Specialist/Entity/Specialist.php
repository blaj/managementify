<?php

namespace App\Specialist\Entity;

use App\Common\Entity\SoftDeleteEntity;
use App\Specialist\Repository\SpecialistRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: SpecialistRepository::class)]
#[Table(name: 'specialist', schema: 'specialist')]
class Specialist extends SoftDeleteEntity {

  #[Column(name: 'firstname', type: Types::STRING, length: 100, nullable: false)]
  private string $firstname;

  #[Column(name: 'surname', type: Types::STRING, length: 100, nullable: false)]
  private string $surname;

  #[Column(name: 'foreign_id', type: Types::STRING, length: 100, nullable: true)]
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