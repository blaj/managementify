<?php

namespace App\User\Mapper;

use App\User\Dto\UserUpdateRequest;
use App\User\Entity\User;

class UserUpdateRequestMapper {

  public static function map(?User $user): ?UserUpdateRequest {
    if ($user === null) {
      return null;
    }

    return (new UserUpdateRequest())
        ->setId($user->getId())
        ->setUsername($user->getUsername())
        ->setEmail($user->getEmail())
        ->setRoleId($user->getRole()?->getId())
        ->setClientId($user->getClient()?->getId())
        ->setSpecialistId($user->getSpecialist()?->getId());
  }
}