<?php

namespace App\Common\Mapper;

use App\Common\Dto\QueryWithCriteriaWrapper;

class QueryWithCriteriaWrapperStatementMapper {

  public static function map(?QueryWithCriteriaWrapper $queryWithCriteriaWrapper): ?string {
    if ($queryWithCriteriaWrapper === null) {
      return null;
    }

    $statements =
        array_filter(
            $queryWithCriteriaWrapper->getStatements(),
            fn (?string $statement) => $statement !== null);

    return (count($statements) > 0 ? ' AND ' : '')
        . implode(' AND ', $statements);
  }
}