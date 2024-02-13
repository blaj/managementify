<?php

namespace App\Common\Mapper;

use App\Common\PaginatedList\Dto\PageCriteria;
use Doctrine\ORM\Query;

class QueryPaginationMapper {

  public static function map(Query $query, ?PageCriteria $pageCriteria): Query {
    $queryPagination = $query;

    $no = $pageCriteria?->getNo() !== null ? $pageCriteria->getNo() : PageCriteria::$defaultNo;
    $size =
        $pageCriteria?->getSize() !== null ? $pageCriteria->getSize() : PageCriteria::$defaultSize;

    $queryPagination
        ->setMaxResults($size)
        ->setFirstResult($size * $no);

    return $queryPagination;
  }
}