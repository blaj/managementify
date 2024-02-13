<?php

namespace App\Tests\Client\Mapper;

use App\Client\Entity\Client;
use App\Client\Mapper\ClientUpdateRequestMapper;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ClientUpdateRequestMapperTest extends TestCase {

  /**
   * @test
   */
  public function givenNull_whenMap_shouldReturnNull(): void {
    // given
    $client = null;

    // when
    $dto = ClientUpdateRequestMapper::map($client);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenNotNull_whenMap_shouldReturnDto(): void {
    // given
    $client = (new Client())
        ->setFirstname('firstname')
        ->setSurname('surname')
        ->setForeignId('foreignId');

    // when
    $dto = ClientUpdateRequestMapper::map($client);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->getFirstname(), $client->getFirstname());
    Assert::assertEquals($dto->getSurname(), $client->getSurname());
    Assert::assertEquals($dto->getForeignId(), $client->getForeignId());
  }
}