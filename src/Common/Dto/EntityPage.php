<?php

namespace App\Common\Dto;

use App\Common\Entity\IdEntity;

/**
 * @template T
 */
class EntityPage {

  /**
   * @template TS
   *
   * @param class-string<TS> $className
   * @param TS ...$items
   *
   * @return EntityPage<TS>
   */
  public static function of(string $className, ...$items): EntityPage {
    return (new EntityPage($className))->setItems($items)->setTotalItems(count($items));
  }

  /**
   * @template TS
   *
   * @param class-string<TS> $className
   *
   * @return EntityPage<TS>
   */
  public static function empty(string $className = IdEntity::class): EntityPage {
    return (new EntityPage($className))->setItems([])->setTotalItems(0);
  }

  /**
   * @var array<T>
   */
  private array $items = [];

  private int $totalItems = 0;

  /**
   * @param class-string<T> $className
   */
  public function __construct(private readonly string $className) {}

  /**
   * @return array<T>
   */
  public function getItems(): array {
    return $this->items;
  }

  /**
   * @param array<T> $items
   *
   * @return self<T>
   */
  public function setItems(array $items): self {
    $this->items = $items;

    return $this;
  }

  public function getTotalItems(): int {
    return $this->totalItems;
  }

  /**
   * @return self<T>
   */
  public function setTotalItems(int $totalItems): self {
    $this->totalItems = $totalItems;

    return $this;
  }
}