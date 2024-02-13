<?php

namespace App\Tests\Common\Mapper;

use App\Common\Mapper\QueryPaginationMapper;
use App\Common\PaginatedList\Dto\PageCriteria;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class QueryPaginationMapperTest extends TestCase {

  private Query $query;

  private EntityManagerInterface $entityManager;

  protected function setUp(): void {
    $this->entityManager = $this->createMock(EntityManager::class);

    $this->entityManager
        ->expects(self::any())
        ->method('getConfiguration')
        ->willReturn(new Configuration());

    $this->query = new Query($this->entityManager);
  }

  /**
   * @test
   */
  public function givenNullPageCriteria_whenMap_shouldReturnQueryWithDefault(): void {
    // given
    $pageCriteria = null;

    // when
    $queryWithPageCriteria = QueryPaginationMapper::map($this->query, $pageCriteria);

    // then
    Assert::assertEquals($queryWithPageCriteria->getMaxResults(), PageCriteria::$defaultSize);
    Assert::assertEquals(
        $queryWithPageCriteria->getFirstResult(),
        PageCriteria::$defaultNo * PageCriteria::$defaultSize);
  }

  /**
   * @test
   */
  public function givenNullNo_whenMap_shouldReturnQueryWithDefaultNo(): void {
    // given
    $no = null;
    $size = 50;

    $pageCriteria = (new PageCriteria())->setNo($no)->setSize($size);

    // when
    $queryWithPageCriteria = QueryPaginationMapper::map($this->query, $pageCriteria);

    // then
    Assert::assertEquals($queryWithPageCriteria->getMaxResults(), $size);
    Assert::assertEquals(
        $queryWithPageCriteria->getFirstResult(),
        PageCriteria::$defaultNo * $size);
  }

  /**
   * @test
   */
  public function givenNullSize_whenMap_shouldReturnQueryWithDefaultSize(): void {
    // given
    $no = 10;
    $size = null;

    $pageCriteria = (new PageCriteria())->setNo($no)->setSize($size);

    // when
    $queryWithPageCriteria = QueryPaginationMapper::map($this->query, $pageCriteria);

    // then
    Assert::assertEquals($queryWithPageCriteria->getMaxResults(), PageCriteria::$defaultSize);
    Assert::assertEquals(
        $queryWithPageCriteria->getFirstResult(),
        $no * PageCriteria::$defaultSize);
  }

  /**
   * @test
   */
  public function givenValid_whenMap_shouldReturnQuery(): void {
    // given
    $no = 10;
    $size = 100;

    $pageCriteria = (new PageCriteria())->setNo($no)->setSize($size);

    // when
    $queryWithPageCriteria = QueryPaginationMapper::map($this->query, $pageCriteria);

    // then
    Assert::assertEquals($queryWithPageCriteria->getMaxResults(), $size);
    Assert::assertEquals(
        $queryWithPageCriteria->getFirstResult(),
        $no * $size);
  }
}