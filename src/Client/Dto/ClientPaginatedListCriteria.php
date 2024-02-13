<?php

namespace App\Client\Dto;

use App\Common\PaginatedList\Dto\PaginatedListCriteria;
use App\Common\PaginatedList\Validator\AllowedSortByField;

/**
 * @extends PaginatedListCriteria<ClientPaginatedListFilter>
 */
#[AllowedSortByField(fields: self::sortableFields)]
class ClientPaginatedListCriteria extends PaginatedListCriteria {

  const sortableFields = [
      'client.firstname',
      'client.surname',
      'client.foreignId'
  ];
}