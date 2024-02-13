<?php

namespace App\Tests\Specialist\Mapper;

use App\Specialist\Entity\Specialist;
use App\Specialist\Mapper\SpecialistUpdateRequestMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SpecialistUpdateRequestMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $specialist = null;

    // when
    $dto = SpecialistUpdateRequestMapper::map($specialist);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnDto(): void {
    // given
    $specialist =
        (new Specialist())
            ->setFirstname('firstname')
            ->setSurname('surname')
            ->setForeignId('foreignId');

    // when
    $dto = SpecialistUpdateRequestMapper::map($specialist);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->getFirstname(), $specialist->getFirstname());
    Assert::assertEquals($dto->getSurname(), $specialist->getSurname());
    Assert::assertEquals($dto->getForeignId(), $specialist->getForeignId());
  }
}