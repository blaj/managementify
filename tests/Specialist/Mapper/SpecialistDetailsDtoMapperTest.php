<?php

namespace App\Tests\Specialist\Mapper;

use App\Specialist\Entity\Specialist;
use App\Specialist\Mapper\SpecialistDetailsDtoMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SpecialistDetailsDtoMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $specialist = null;

    // when
    $dto = SpecialistDetailsDtoMapper::map($specialist);

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
            ->setId(123)
            ->setFirstname('firstname')
            ->setSurname('surname')
            ->setForeignId('foreignId');

    // when
    $dto = SpecialistDetailsDtoMapper::map($specialist);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->id, $specialist->getId());
    Assert::assertEquals($dto->firstname, $specialist->getFirstname());
    Assert::assertEquals($dto->surname, $specialist->getSurname());
    Assert::assertEquals($dto->foreignId, $specialist->getForeignId());
  }
}