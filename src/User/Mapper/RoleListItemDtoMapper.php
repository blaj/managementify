<?php

namespace App\User\Mapper;

use App\User\Dto\RoleListItemDto;
use App\User\Entity\Role;

class RoleListItemDtoMapper {

  public static function map(?Role $role): ?RoleListItemDto {
    if ($role === null) {
      return null;
    }

    return new RoleListItemDto(
        $role->getId(),
        $role->getCode(),
        $role->getName(),
        $role->isArchived());
  }
}