<?php

namespace App\Tests\Visit\Mapper;

use App\Company\Entity\Company;
use App\Visit\Entity\VisitType;
use App\Visit\Mapper\VisitTypeUpdateRequestMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class VisitTypeUpdateRequestMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $visitType = null;

    // when
    $dto = VisitTypeUpdateRequestMapper::map($visitType);

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
        ->setArchived(true)
        ->setCompany((new Company())->setId(111));

    // when
    $dto = VisitTypeUpdateRequestMapper::map($visitType);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->getId(), $visitType->getId());
    Assert::assertEquals($dto->getName(), $visitType->getName());
    Assert::assertEquals($dto->getCode(), $visitType->getCode());
    Assert::assertEquals($dto->getPreferredPrice(), $visitType->getPreferredPrice());
    Assert::assertEquals($dto->getCompanyId(), $visitType->getCompany()->getId());
  }
}