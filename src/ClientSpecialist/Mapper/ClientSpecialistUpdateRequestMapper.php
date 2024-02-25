<?php

namespace App\ClientSpecialist\Mapper;

use App\ClientSpecialist\Dto\ClientSpecialistUpdateRequest;
use App\ClientSpecialist\Entity\ClientSpecialist;

class ClientSpecialistUpdateRequestMapper {

  public static function map(?ClientSpecialist $clientSpecialist): ?ClientSpecialistUpdateRequest {
    if ($clientSpecialist === null) {
      return null;
    }

    return (new ClientSpecialistUpdateRequest())
        ->setId($clientSpecialist->getId())
        ->setFromDate($clientSpecialist->getFromDate())
        ->setToDate($clientSpecialist->getToDate())
        ->setAssignType($clientSpecialist->getAssignType())
        ->setClientId($clientSpecialist->getClient()->getId())
        ->setSpecialistId($clientSpecialist->getSpecialist()->getId())
        ->setCompanyId($clientSpecialist->getCompany()->getId());
  }
}