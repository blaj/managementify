<?php

namespace App\Company\Mapper;

use App\Company\Dto\CompanyDetailsDto;
use App\Company\Entity\Company;

class CompanyDetailsDtoMapper {

  public static function map(?Company $company): ?CompanyDetailsDto {
    if ($company === null) {
      return null;
    }

    return new CompanyDetailsDto(
        $company->getId(),
        $company->getName(),
        $company->getAddress()->getCity(),
        $company->getAddress()->getStreet(),
        $company->getAddress()->getPostcode());
  }
}