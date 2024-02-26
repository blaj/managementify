<?php

namespace App\User\Mapper;

use App\User\Dto\RoleDetailsDto;
use App\User\Entity\PermissionType;
use App\User\Entity\Role;
use App\User\Entity\RolePermission;

class RoleDetailsDtoMapper {

  public static function map(?Role $role): ?RoleDetailsDto {
    if ($role === null) {
      return null;
    }

    return new RoleDetailsDto(
        $role->getId(),
        $role->getCode(),
        $role->getName(),
        $role->isArchived(),
        self::permissionTypes($role->getPermissions()->toArray()));
  }

  /**
   * @param array<RolePermission> $rolePermissions
   *
   * @return array<PermissionType>
   */
  private static function permissionTypes(array $rolePermissions): array {
    return array_map(fn (RolePermission $rolePermission) => $rolePermission->getType(),
        $rolePermissions);
  }
}