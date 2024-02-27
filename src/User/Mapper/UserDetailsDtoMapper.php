<?php

namespace App\User\Mapper;

use App\Client\Entity\Client;
use App\Specialist\Entity\Specialist;
use App\User\Dto\UserDetailsDto;
use App\User\Entity\User;

class UserDetailsDtoMapper {

  public static function map(?User $user): ?UserDetailsDto {
    if ($user === null) {
      return null;
    }

    return new UserDetailsDto(
        $user->getId(),
        $user->getUsername(),
        $user->getEmail(),
        $user->getRole()?->getName(),
        self::specialistName($user->getSpecialist()),
        self::clientName($user->getClient()));
  }

  private static function specialistName(?Specialist $specialist): ?string {
    if ($specialist === null) {
      return null;
    }

    return $specialist->getSurname() . ' ' . $specialist->getFirstname();
  }

  private static function clientName(?Client $client): ?string {
    if ($client === null) {
      return null;
    }

    return $client->getSurname() . ' ' . $client->getFirstname();
  }
}