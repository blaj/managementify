<?php

namespace App\Common\PaginatedList\Mapper;

use App\Common\Dto\EntityPage;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\Page;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Common\PaginatedList\Dto\PaginatedListCriteria;

class PageMapper {

  /**
   * @template T
   * @template F
   *
   * @param class-string<T> $entityClassName
   * @param class-string<F> $filterClassName
   * @param CriteriaWithEntityPageWrapper<T, F>|null $criteriaWithEntityPageWrapper
   *
   * @return Page
   */
  public static function map(
      string $entityClassName,
      string $filterClassName,
      ?CriteriaWithEntityPageWrapper $criteriaWithEntityPageWrapper): Page {
    if ($criteriaWithEntityPageWrapper === null
        || $criteriaWithEntityPageWrapper->getCriteria() === null
        || $criteriaWithEntityPageWrapper->getEntityPage() === null) {
      return self::map(
          $entityClassName,
          $filterClassName,
          (new CriteriaWithEntityPageWrapper($entityClassName, $filterClassName))
              ->setCriteria(
                  (new PaginatedListCriteria(
                      $filterClassName,
                      $criteriaWithEntityPageWrapper?->getCriteria()?->getFilter()))
                      ->setPageCriteria(clone PageCriteria::default()))
              ->setEntityPage(clone EntityPage::empty($entityClassName)));
    }

    $criteria = $criteriaWithEntityPageWrapper->getCriteria();
    $entityPage = $criteriaWithEntityPageWrapper->getEntityPage();

    return (new Page())
        ->setNo($criteria->getPageCriteria()?->getNo())
        ->setSize($criteria->getPageCriteria()?->getSize())
        ->setTotalItems($entityPage->getTotalItems());
  }
}