<?php

namespace App\ClientSpecialist\Mapper;

use App\Client\Entity\Client;
use App\ClientSpecialist\Dto\ClientSpecialistListItemDto;
use App\ClientSpecialist\Entity\ClientSpecialist;
use App\Specialist\Entity\Specialist;

class ClientSpecialistListItemDtoMapper {

  public static function map(?ClientSpecialist $clientSpecialist): ?ClientSpecialistListItemDto {
    if ($clientSpecialist === null) {
      return null;
    }

    return new ClientSpecialistListItemDto(
        $clientSpecialist->getId(),
        self::clientName($clientSpecialist->getClient()),
        self::specialistName($clientSpecialist->getSpecialist()),
        $clientSpecialist->getFromDate(),
        $clientSpecialist->getToDate(),
        $clientSpecialist->getAssignType());
  }

  private static function clientName(Client $client): string {
    return $client->getSurname() . ' ' . $client->getFirstname();
  }

  private static function specialistName(Specialist $specialist): string {
    return $specialist->getSurname() . ' ' . $specialist->getFirstname();
  }
}