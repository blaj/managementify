<?php

namespace App\Client\Mapper;

use App\Client\Dto\ClientDetailsDto;
use App\Client\Entity\Client;

class ClientDetailsDtoMapper {

  public static function map(?Client $client): ?ClientDetailsDto {
    if ($client === null) {
      return null;
    }

    return new ClientDetailsDto(
        $client->getId(),
        $client->getFirstname(),
        $client->getSurname(),
        $client->getForeignId());
  }
}