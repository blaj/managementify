<?php

namespace App\Tests\Common\Mapper;

use App\Common\Dto\QueryWithCriteriaWrapper;
use App\Common\Mapper\QueryWithCriteriaWrapperStatementMapper;
use PHPUnit\Framework\TestCase;

class QueryWithCriteriaWrapperStatementMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $queryWithCriteriaWrapper = null;

    // when
    $statement = QueryWithCriteriaWrapperStatementMapper::map($queryWithCriteriaWrapper);

    // then
    static::assertNull($statement);
  }

  /**
   * @test
   */
  public function givenValid_whenMap_shouldReturnStatement(): void {
    // given
    $statement1 = " example = :example ";
    $statement2 = " example2 = :example2 ";

    $queryWithCriteriaWrapper =
        (new QueryWithCriteriaWrapper())
            ->setStatements([$statement1, $statement2]);

    // when
    $statement = QueryWithCriteriaWrapperStatementMapper::map($queryWithCriteriaWrapper);

    // then
    static::assertNotNull($statement);
    static::assertEquals(' AND ' . $statement1 . ' AND ' . $statement2, $statement);
  }
}