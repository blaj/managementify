<?php

namespace App\Specialist\Mapper;

use App\Specialist\Dto\SpecialistUpdateRequest;
use App\Specialist\Entity\Specialist;

class SpecialistUpdateRequestMapper {

  public static function map(?Specialist $specialist): ?SpecialistUpdateRequest {
    if ($specialist === null) {
      return null;
    }

    return (new SpecialistUpdateRequest())
        ->setFirstname($specialist->getFirstname())
        ->setSurname($specialist->getSurname())
        ->setForeignId($specialist->getForeignId());
  }
}