<?php

namespace App\User\Mapper;

use App\User\Dto\UserListItemDto;
use App\User\Entity\User;

class UserListItemDtoMapper {

  public static function map(?User $user): ?UserListItemDto {
    if ($user === null) {
      return null;
    }

    return new UserListItemDto($user->getId(), $user->getUsername(), $user->getEmail());
  }
}