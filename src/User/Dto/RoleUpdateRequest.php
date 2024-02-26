<?php

namespace App\User\Dto;

use App\User\Entity\PermissionType;

class RoleUpdateRequest {

  private string $code;

  private string $name;

  /**
   * @var array<PermissionType>
   */
  private array $permissionTypes = [];

  public function getCode(): string {
    return $this->code;
  }

  public function setCode(string $code): self {
    $this->code = $code;

    return $this;
  }

  public function getName(): string {
    return $this->name;
  }

  public function setName(string $name): self {
    $this->name = $name;

    return $this;
  }

  /**
   * @return array<PermissionType>
   */
  public function getPermissionTypes(): array {
    return $this->permissionTypes;
  }

  /**
   * @param array<PermissionType> $permissionTypes
   */
  public function setPermissionTypes(array $permissionTypes): self {
    $this->permissionTypes = $permissionTypes;

    return $this;
  }
}