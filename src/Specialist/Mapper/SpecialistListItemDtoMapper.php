<?php

namespace App\Specialist\Mapper;

use App\Specialist\Dto\SpecialistListItemDto;
use App\Specialist\Entity\Specialist;

class SpecialistListItemDtoMapper {

  public static function map(?Specialist $specialist): ?SpecialistListItemDto {
    if ($specialist === null) {
      return null;
    }

    return new SpecialistListItemDto(
        $specialist->getId(),
        $specialist->getFirstname(),
        $specialist->getSurname(),
        $specialist->getForeignId());
  }
}