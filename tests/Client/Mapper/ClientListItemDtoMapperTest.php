<?php

namespace App\Tests\Client\Mapper;

use App\Client\Entity\Client;
use App\Client\Mapper\ClientListItemDtoMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ClientListItemDtoMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $client = null;

    // when
    $dto = ClientListItemDtoMapper::map($client);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnDto(): void {
    // given
    $client = (new Client())
        ->setId(123)
        ->setFirstname('firstname')
        ->setSurname('surname')
        ->setForeignId('foreignId');

    // when
    $dto = ClientListItemDtoMapper::map($client);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->id, $client->getId());
    Assert::assertEquals($dto->firstname, $client->getFirstname());
    Assert::assertEquals($dto->surname, $client->getSurname());
    Assert::assertEquals($dto->foreignId, $client->getForeignId());
  }
}