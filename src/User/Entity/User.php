<?php

namespace App\User\Entity;

use App\Client\Entity\Client;
use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\SoftDeleteEntity;
use App\Company\Entity\Company;
use App\Specialist\Entity\Specialist;
use App\User\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[Entity(repositoryClass: UserRepository::class)]
#[Table(name: 'users', schema: 'users')]
class User extends SoftDeleteEntity implements CompanyContextInterface, PasswordAuthenticatedUserInterface {

  #[Column(name: 'username', type: Types::STRING, length: 50, nullable: false)]
  private string $username;

  #[Column(name: 'password', type: Types::STRING, length: 200, nullable: false)]
  private string $password;

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;

  #[Column(name: 'email', type: Types::STRING, length: 100, nullable: false)]
  private string $email;

  #[JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  #[ManyToOne(targetEntity: Role::class, fetch: 'LAZY', inversedBy: 'users')]
  private ?Role $role = null;

  #[JoinColumn(name: 'client_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  #[ManyToOne(targetEntity: Client::class, fetch: 'LAZY', inversedBy: 'users')]
  private ?Client $client = null;

  #[JoinColumn(name: 'specialist_id', referencedColumnName: 'id', nullable: true, columnDefinition: 'BIGINT')]
  #[ManyToOne(targetEntity: Specialist::class, fetch: 'LAZY', inversedBy: 'users')]
  private ?Specialist $specialist = null;

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

  public function getCompany(): Company {
    return $this->company;
  }

  public function setCompany(Company $company): self {
    $this->company = $company;

    return $this;
  }

  public function getEmail(): string {
    return $this->email;
  }

  public function setEmail(string $email): self {
    $this->email = $email;

    return $this;
  }

  public function getRole(): ?Role {
    return $this->role;
  }

  public function setRole(?Role $role): self {
    $this->role = $role;

    return $this;
  }

  public function getClient(): ?Client {
    return $this->client;
  }

  public function setClient(?Client $client): self {
    $this->client = $client;

    return $this;
  }

  public function getSpecialist(): ?Specialist {
    return $this->specialist;
  }

  public function setSpecialist(?Specialist $specialist): self {
    $this->specialist = $specialist;

    return $this;
  }
}