<?php

namespace App\Tests\Common\PaginatedList\Mapper;

use App\Common\Dto\EntityPage;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Common\PaginatedList\Dto\PaginatedListCriteria;
use App\Common\PaginatedList\Dto\Sort;
use App\Common\PaginatedList\Mapper\PageMapper;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Entity\Specialist;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class PageMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnDefaultPage(): void {
    // given
    $criteriaWithEntityPageWrapper = null;

    // when
    $page =
        PageMapper::map(Specialist::class, SpecialistPaginatedListFilter::class, $criteriaWithEntityPageWrapper);

    // then
    Assert::assertNotNull($page);
    Assert::assertEquals($page->getNo(), PageCriteria::default()->getNo());
    Assert::assertEquals($page->getSize(), PageCriteria::default()->getSize());
    Assert::assertEquals(0, $page->getTotalItems());
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnPage(): void {
    // given
    $entityClass = Specialist::class;
    $filterClass = SpecialistPaginatedListFilter::class;

    $criteriaWithEntityPageWrapper =
        (new CriteriaWithEntityPageWrapper($entityClass, $filterClass))
            ->setCriteria(
                (new PaginatedListCriteria($filterClass))->setPageCriteria(
                    (new PageCriteria())->setSize(10)->setNo(5))->setSort(
                    (new Sort())->setBy('some.field')->setOrder(Order::DESC)))
            ->setEntityPage(EntityPage::of(Specialist::class, new Specialist(), new Specialist()));

    // when
    $page = PageMapper::map($entityClass, $filterClass, $criteriaWithEntityPageWrapper);

    // then
    Assert::assertNotNull($page);
    Assert::assertEquals(
        $page->getSize(),
        $criteriaWithEntityPageWrapper->getCriteria()->getPageCriteria()->getSize());
    Assert::assertEquals(
        $page->getNo(),
        $criteriaWithEntityPageWrapper->getCriteria()->getPageCriteria()->getNo());
    Assert::assertEquals(
        $page->getTotalItems(),
        $criteriaWithEntityPageWrapper->getEntityPage()->getTotalItems());
  }
}