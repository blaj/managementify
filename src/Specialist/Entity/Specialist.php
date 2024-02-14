<?php

namespace App\Specialist\Entity;

use App\Common\Entity\SoftDeleteEntity;
use App\Specialist\Repository\SpecialistRepository;
use App\Visit\Entity\Visit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToMany;
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

  /**
   * @var Collection<int, Visit>
   */
  #[OneToMany(targetEntity: Visit::class, mappedBy: 'specialist')]
  private Collection $visits;

  public function __construct() {
    $this->visits = new ArrayCollection();
  }

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

  /**
   * @return Collection<int, Visit>
   */
  public function getVisits(): Collection {
    return $this->visits;
  }

  /**
   * @param Collection<int, Visit> $visits
   */
  public function setVisits(Collection $visits): self {
    $this->visits = $visits;

    return $this;
  }

  public function addVisit(Visit $visit): self {
    if (!$this->visits->contains($visit)) {
      $this->visits->add($visit);
    }

    return $this;
  }

  public function removeVisit(Visit $visit): self {
    $this->visits->removeElement($visit);

    return $this;
  }
}