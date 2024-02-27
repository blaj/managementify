<?php

namespace App\User\Dto;

use App\Common\PaginatedList\Dto\PaginatedListCriteria;
use App\Common\PaginatedList\Validator\AllowedSortByField;

/**
 * @extends PaginatedListCriteria<UserPaginatedListFilter>
 */
#[AllowedSortByField(fields: self::sortableFields)]
class UserPaginatedListCriteria extends PaginatedListCriteria {

  const sortableFields = [
      'user.username',
      'user.email'
  ];
}