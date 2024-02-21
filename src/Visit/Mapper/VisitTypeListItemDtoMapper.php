<?php

namespace App\Visit\Mapper;

use App\Visit\Dto\VisitTypeListItemDto;
use App\Visit\Entity\VisitType;

class VisitTypeListItemDtoMapper {

  public static function map(?VisitType $visitType): ?VisitTypeListItemDto {
    if ($visitType === null) {
      return null;
    }

    return new VisitTypeListItemDto(
        $visitType->getId(),
        $visitType->getCode(),
        $visitType->getName(),
        $visitType->getPreferredPrice(),
        $visitType->isArchived());
  }
}