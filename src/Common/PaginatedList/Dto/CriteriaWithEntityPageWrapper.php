<?php

namespace App\Common\PaginatedList\Dto;

use App\Common\Dto\EntityPage;

/**
 * @template T
 * @template F
 */
class CriteriaWithEntityPageWrapper {

  /**
   * @var EntityPage<T>|null
   */
  private ?EntityPage $entityPage = null;

  /**
   * @var PaginatedListCriteria<F>|null
   */
  private ?PaginatedListCriteria $criteria = null;

  /**
   * @param class-string<T> $entityClassName
   * @param class-string<F> $filterClassName
   */
  public function __construct(
    /** @phpstan-ignore-line */ private readonly string $entityClassName,
      /** @phpstan-ignore-line */ private readonly string $filterClassName) {}

  /**
   * @return EntityPage<T>|null
   */
  public function getEntityPage(): ?EntityPage {
    return $this->entityPage;
  }

  /**
   * @param EntityPage<T>|null $entityPage
   *
   * @return self<T, F>
   */
  public function setEntityPage(?EntityPage $entityPage): self {
    $this->entityPage = $entityPage;

    return $this;
  }

  /**
   * @return PaginatedListCriteria<F>|null
   */
  public function getCriteria(): ?PaginatedListCriteria {
    return $this->criteria;
  }

  /**
   * @param PaginatedListCriteria<F>|null $criteria
   *
   * @return self<T, F>
   */
  public function setCriteria(?PaginatedListCriteria $criteria): self {
    $this->criteria = $criteria;

    return $this;
  }
}