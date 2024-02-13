<?php

namespace App\Client\Mapper;

use App\Client\Dto\ClientUpdateRequest;
use App\Client\Entity\Client;

class ClientUpdateRequestMapper {

  public static function map(?Client $client): ?ClientUpdateRequest {
    if ($client === null) {
      return null;
    }
    
    return (new ClientUpdateRequest())
        ->setFirstname($client->getFirstname())
        ->setSurname($client->getSurname())
        ->setForeignId($client->getForeignId());
  }
}