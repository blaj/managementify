<?php

namespace App\Common\PaginatedList\Dto;

/**
 * @template F
 */
class PaginatedListCriteria {

  protected ?PageCriteria $pageCriteria = null;

  protected ?Sort $sort = null;

  /**
   * @param class-string $filterClassName
   * @param F|null $filter
   */
  public function __construct(private readonly string $filterClassName, protected $filter = null) {}

  /**
   * @return F|null
   */
  public function getFilter(): mixed {
    return $this->filter;
  }

  /**
   * @param F|null $filter
   *
   * @return self<F>
   */
  public function setFilter($filter): self {
    $this->filter = $filter;

    return $this;
  }

  public function getPageCriteria(): ?PageCriteria {
    return $this->pageCriteria;
  }

  /**
   * @return self<F>
   */
  public function setPageCriteria(?PageCriteria $pageCriteria): self {
    $this->pageCriteria = $pageCriteria;

    return $this;
  }

  public function getSort(): ?Sort {
    return $this->sort;
  }

  /**
   * @return self<F>
   */
  public function setSort(?Sort $sort): self {
    $this->sort = $sort;

    return $this;
  }
}