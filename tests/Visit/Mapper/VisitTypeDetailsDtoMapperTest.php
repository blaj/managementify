<?php

namespace App\Tests\Visit\Mapper;

use App\Visit\Entity\VisitType;
use App\Visit\Mapper\VisitTypeDetailsDtoMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class VisitTypeDetailsDtoMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $visitType = null;

    // when
    $dto = VisitTypeDetailsDtoMapper::map($visitType);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnDto(): void {
    // given
    $visitType = (new VisitType())
        ->setId(123)
        ->setName('name')
        ->setCode('code')
        ->setPreferredPrice(100)
        ->setArchived(true);

    // when
    $dto = VisitTypeDetailsDtoMapper::map($visitType);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->id, $visitType->getId());
    Assert::assertEquals($dto->name, $visitType->getName());
    Assert::assertEquals($dto->code, $visitType->getCode());
    Assert::assertEquals($dto->preferredPrice, $visitType->getPreferredPrice());
    Assert::assertEquals($dto->archived, $visitType->isArchived());
  }
}