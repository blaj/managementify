<?php

namespace App\Tests\Specialist\Mapper;

use App\Specialist\Entity\Specialist;
use App\Specialist\Mapper\SpecialistListItemDtoMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SpecialistListItemDtoMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $specialist = null;

    // when
    $dto = SpecialistListItemDtoMapper::map($specialist);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnDto(): void {
    // given
    $specialist = (new Specialist())
        ->setId(123)
        ->setFirstname('firstname')
        ->setSurname('surname')
        ->setForeignId('foreignId');

    // when
    $dto = SpecialistListItemDtoMapper::map($specialist);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($specialist->getId(), $dto->id);
    Assert::assertEquals($specialist->getFirstname(), $dto->firstname);
    Assert::assertEquals($specialist->getSurname(), $dto->surname);
    Assert::assertEquals($specialist->getForeignId(), $dto->foreignId);
  }
}