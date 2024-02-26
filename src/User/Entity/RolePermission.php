<?php

namespace App\User\Entity;

use App\Common\Entity\SoftDeleteEntity;
use App\User\Repository\RolePermissionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: RolePermissionRepository::class)]
#[Table(name: 'role_permission', schema: 'users')]
class RolePermission extends SoftDeleteEntity {

  #[JoinColumn(name: 'role_id', referencedColumnName: 'id', nullable: false, columnDefinition: 'BIGINT')]
  #[ManyToOne(targetEntity: Role::class, fetch: 'LAZY', inversedBy: 'permissions')]
  private Role $role;

  #[Column(name: 'type', type: Types::STRING, length: 100, nullable: false, enumType: PermissionType::class)]
  private PermissionType $type;

  public function getRole(): Role {
    return $this->role;
  }

  public function setRole(Role $role): self {
    $this->role = $role;

    return $this;
  }

  public function getType(): PermissionType {
    return $this->type;
  }

  public function setType(PermissionType $type): self {
    $this->type = $type;

    return $this;
  }
}