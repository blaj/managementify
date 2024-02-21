<?php

namespace App\Visit\Mapper;

use App\Visit\Dto\VisitTypeDetailsDto;
use App\Visit\Entity\VisitType;

class VisitTypeDetailsDtoMapper {

  public static function map(?VisitType $visitType): ?VisitTypeDetailsDto {
    if ($visitType === null) {
      return null;
    }

    return new VisitTypeDetailsDto(
        $visitType->getId(),
        $visitType->getCode(),
        $visitType->getName(),
        $visitType->getPreferredPrice(),
        $visitType->isArchived());
  }
}