<?php

namespace App\User\Dto;

use App\User\Entity\PermissionType;

readonly class RoleDetailsDto {

  /**
   * @param array<PermissionType> $permissionTypes
   */
  public function __construct(
      public int $id,
      public string $code,
      public string $name,
      public bool $archived,
      public array $permissionTypes) {}
}