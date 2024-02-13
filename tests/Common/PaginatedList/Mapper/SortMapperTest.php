<?php

namespace App\Tests\Common\PaginatedList\Mapper;

use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\Sort;
use App\Common\PaginatedList\Mapper\SortMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SortMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnStringWithDefault(): void {
    // given
    $sort = null;
    $defaultBy = 'default.id';

    // when
    $string = SortMapper::map($sort, $defaultBy);

    // then
    Assert::assertStringContainsString(
        ' ORDER BY ' . $defaultBy . ' ' . Order::ASC->name . ' ',
        $string);
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnStringWithSortField(): void {
    // given
    $sort = (new Sort())->setBy('field.id')->setOrder(Order::DESC);
    $defaultBy = 'default.id';

    // when
    $string = SortMapper::map($sort, $defaultBy);

    // then
    Assert::assertStringContainsString(
        ' ORDER BY ' . $sort->getBy() . ' ' . $sort->getOrder()->name . ' ',
        $string);
  }
}