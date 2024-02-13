<?php

namespace App\Specialist\Dto;

use App\Common\PaginatedList\Dto\PaginatedListCriteria;
use App\Common\PaginatedList\Validator\AllowedSortByField;

/**
 * @extends PaginatedListCriteria<SpecialistPaginatedListFilter>
 */
#[AllowedSortByField(fields: self::sortableFields)]
class SpecialistPaginatedListCriteria extends PaginatedListCriteria {

  const sortableFields = [
    'specialist.firstname',
    'specialist.surname',
    'specialist.foreignId'
  ];
}