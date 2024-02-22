<?php

namespace App\Client\Mapper;

use App\Client\Dto\PreferredHourUpdateRequest;
use App\Client\Entity\PreferredHour;

class PreferredHourUpdateRequestMapper {

  public static function map(?PreferredHour $preferredHour): ?PreferredHourUpdateRequest {
    if ($preferredHour === null) {
      return null;
    }

    return (new PreferredHourUpdateRequest())
        ->setId($preferredHour->getId())
        ->setClientId($preferredHour->getClient()->getId())
        ->setFromTime($preferredHour->getFromTime())
        ->setToTime($preferredHour->getToTime())
        ->setCompanyId($preferredHour->getCompany()->getId());
  }
}