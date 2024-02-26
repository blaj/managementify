<?php

namespace App\User\Entity;

use App\Common\Entity\Dictionary;
use App\Company\Entity\Company;
use App\User\Repository\RoleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: RoleRepository::class)]
#[Table(name: 'role', schema: 'users')]
class Role extends Dictionary {

  #[JoinColumn(name: 'company_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT NOT NULL')]
  #[ManyToOne(targetEntity: Company::class, fetch: 'LAZY')]
  private Company $company;


  /**
   * @var Collection<int, User>
   */
  #[OneToMany(mappedBy: 'role', targetEntity: User::class)]
  private Collection $users;

  /**
   * @var Collection<int, RolePermission>
   */
  #[OneToMany(mappedBy: 'role', targetEntity: RolePermission::class)]
  private Collection $permissions;

  public function __construct() {
    $this->users = new ArrayCollection();
    $this->permissions = new ArrayCollection();
  }

  public function getCompany(): Company {
    return $this->company;
  }

  public function setCompany(Company $company): self {
    $this->company = $company;

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
      $user->setRole($this);
    }

    return $this;
  }

  public function removeUser(User $user): self {
    $this->users->removeElement($user);

    if ($user->getRole() === $this) {
      $user->setRole(null);
    }

    return $this;
  }

  /**
   * @return Collection<int, RolePermission>
   */
  public function getPermissions(): Collection {
    return $this->permissions;
  }

  /**
   * @param Collection<int, RolePermission> $permissions
   */
  public function setPermissions(Collection $permissions): self {
    $this->permissions = $permissions;

    return $this;
  }

  public function addPermission(RolePermission $permission): self {
    if (!$this->permissions->contains($permission)) {
      $this->permissions->add($permission);
      $permission->setRole($this);
    }

    return $this;
  }

  public function removePermission(RolePermission $permission): self {
    $this->permissions->removeElement($permission);

    return $this;
  }
}