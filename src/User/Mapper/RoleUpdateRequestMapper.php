<?php

namespace App\User\Mapper;

use App\User\Dto\RoleUpdateRequest;
use App\User\Entity\PermissionType;
use App\User\Entity\Role;
use App\User\Entity\RolePermission;

class RoleUpdateRequestMapper {

  public static function map(?Role $role): ?RoleUpdateRequest {
    if ($role === null) {
      return null;
    }

    return (new RoleUpdateRequest())
        ->setCode($role->getCode())
        ->setName($role->getName())
        ->setPermissionTypes(self::permissionTypes($role->getPermissions()->toArray()));
  }

  /**
   * @param array<RolePermission> $rolePermissions
   *
   * @return array<PermissionType>
   */
  private static function permissionTypes(array $rolePermissions): array {
    return array_map(
        fn (RolePermission $rolePermission) => $rolePermission->getType(),
        $rolePermissions);
  }
}