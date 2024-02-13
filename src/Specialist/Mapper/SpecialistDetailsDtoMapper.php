<?php

namespace App\Specialist\Mapper;

use App\Specialist\Dto\SpecialistDetailsDto;
use App\Specialist\Entity\Specialist;

class SpecialistDetailsDtoMapper {

  public static function map(?Specialist $specialist): ?SpecialistDetailsDto {
    if ($specialist === null) {
      return null;
    }

    return new SpecialistDetailsDto(
        $specialist->getId(),
        $specialist->getFirstname(),
        $specialist->getSurname(),
        $specialist->getForeignId());
  }
}