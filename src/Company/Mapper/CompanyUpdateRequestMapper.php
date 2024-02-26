<?php

namespace App\Company\Mapper;

use App\Company\Dto\CompanyUpdateRequest;
use App\Company\Entity\Company;

class CompanyUpdateRequestMapper {

  public static function map(?Company $company): ?CompanyUpdateRequest {
    if ($company === null) {
      return null;
    }

    return (new CompanyUpdateRequest())
        ->setId($company->getId())
        ->setName($company->getName())
        ->setCity($company->getAddress()->getCity())
        ->setStreet($company->getAddress()->getStreet())
        ->setPostcode($company->getAddress()->getPostcode());
  }
}