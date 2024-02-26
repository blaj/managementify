<?php

namespace App\Client\Entity;

use App\Client\Repository\ClientRepository;
use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\SoftDeleteEntity;
use App\Company\Entity\Company;
use App\User\Entity\User;
use App\Visit\Entity\Visit;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: ClientRepository::class)]
#[Table(name: 'client', schema: 'client')]
class Client extends SoftDeleteEntity implements CompanyContextInterface {

  #[Column(name: 'firstname', type: Types::STRING, length: 100, nullable: false)]
  private string $firstname;

  #[Column(name: 'surname', type: Types::STRING, length: 100, nullable: false)]
  private string $surname;

  #[Column(name: 'foreign_id', type: Types::STRING, length: 100, nullable: true)]
  private ?string $foreignId = null;

  /**
   * @var Collection<int, Visit>
   */
  #[OneToMany(targetEntity: Visit::class, mappedBy: 'client')]
  private Collection $visits;

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;

  /**
   * @var Collection<int, PreferredHour>
   */
  #[OneToMany(targetEntity: PreferredHour::class, mappedBy: 'client')]
  private Collection $preferredHours;

  /**
   * @var Collection<int, User>
   */
  #[OneToMany(targetEntity: User::class, mappedBy: 'client')]
  private Collection $users;

  public function __construct() {
    $this->visits = new ArrayCollection();
    $this->preferredHours = new ArrayCollection();
    $this->users = new ArrayCollection();
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

  public function getCompany(): Company {
    return $this->company;
  }

  public function setCompany(Company $company): self {
    $this->company = $company;

    return $this;
  }

  /**
   * @return Collection<int, PreferredHour>
   */
  public function getPreferredHours(): Collection {
    return $this->preferredHours;
  }

  /**
   * @param Collection<int, PreferredHour> $preferredHours
   */
  public function setPreferredHours(Collection $preferredHours): self {
    $this->preferredHours = $preferredHours;

    return $this;
  }

  public function addPreferredHour(PreferredHour $preferredHour): self {
    if (!$this->preferredHours->contains($preferredHour)) {
      $this->preferredHours->add($preferredHour);
    }

    return $this;
  }

  public function removePreferredHour(PreferredHour $preferredHour): self {
    $this->preferredHours->removeElement($preferredHour);

    return $this;
  }

  /**
   * @return Collection<int, User>
   */
  public function getUsers(): Collection {
    return $this->users;
  }

  /**
   * @param Collection<int, User> $users
   */
  public function setUsers(Collection $users): self {
    $this->users = $users;

    return $this;
  }

  public function addUser(User $user): self {
    if (!$this->users->contains($user)) {
      $this->users->add($user);
    }

    return $this;
  }

  public function removeUser(User $user): self {
    $this->users->removeElement($user);

    return $this;
  }
}