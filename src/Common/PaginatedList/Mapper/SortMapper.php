<?php

namespace App\Common\PaginatedList\Mapper;

use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\Sort;

class SortMapper {

  public static function map(?Sort $sort, string $defaultFieldSort): string {
    $sortBy = $defaultFieldSort;
    $direction = '';

    if ($sort !== null) {
      $sortBy = $sort->getBy() ?? $defaultFieldSort;
      $direction = $sort->getOrder() ?? '';
    }

    return ' ORDER BY '
        . $sortBy
        . ' '
        . ($direction == '' || $direction == Order::ASC ? Order::ASC->name : Order::DESC->name)
        . ' ';
  }
}