<?php

namespace App\Client\Mapper;

use App\Client\Dto\ClientListItemDto;
use App\Client\Entity\Client;

class ClientListItemDtoMapper {

  public static function map(?Client $client): ?ClientListItemDto {
    if ($client === null) {
      return null;
    }

    return new ClientListItemDto(
        $client->getId(),
        $client->getFirstname(),
        $client->getSurname(),
        $client->getForeignId());
  }
}