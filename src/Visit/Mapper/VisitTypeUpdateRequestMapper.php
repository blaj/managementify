<?php

namespace App\Visit\Mapper;

use App\Visit\Dto\VisitTypeUpdateRequest;
use App\Visit\Entity\VisitType;

class VisitTypeUpdateRequestMapper {

  public static function map(?VisitType $visitType): ?VisitTypeUpdateRequest {
    if ($visitType === null) {
      return null;
    }

    return (new VisitTypeUpdateRequest())
        ->setId($visitType->getId())
        ->setName($visitType->getName())
        ->setCode($visitType->getCode())
        ->setPreferredPrice($visitType->getPreferredPrice())
        ->setCompanyId($visitType->getCompany()->getId());
  }
}