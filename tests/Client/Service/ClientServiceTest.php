<?php

namespace App\Tests\Client\Service;

use App\Client\Dto\ClientCreateRequest;
use App\Client\Dto\ClientPaginatedListCriteria;
use App\Client\Dto\ClientPaginatedListFilter;
use App\Client\Dto\ClientUpdateRequest;
use App\Client\Entity\Client;
use App\Client\Mapper\ClientDetailsDtoMapper;
use App\Client\Mapper\ClientListItemDtoMapper;
use App\Client\Mapper\ClientUpdateRequestMapper;
use App\Client\Repository\ClientRepository;
use App\Client\Service\ClientService;
use App\Common\Dto\EntityPage;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\Sort;
use App\Common\PaginatedList\Mapper\PageMapper;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class ClientServiceTest extends TestCase {

  private ClientRepository $clientRepository;

  private ClientService $clientService;

  public function setUp(): void {
    $this->clientRepository = $this->createMock(ClientRepository::class);

    $this->clientService = new ClientService($this->clientRepository);
  }

  /**
   * @test
   */
  public function givenNonExistingClients_whenGetPaginatedListByCriteria_shouldReturnEmptyPaginatedList(): void {
    // given
    $criteria = new ClientPaginatedListCriteria(ClientPaginatedListFilter::class);

    $this->clientRepository
        ->expects(static::once())
        ->method('findAllByCriteria')
        ->with($criteria)
        ->willReturn(EntityPage::empty(Client::class));

    // when
    $paginatedList = $this->clientService->getPaginatedListByCriteria($criteria);

    // then
    Assert::assertEmpty($paginatedList->getItems());
  }

  /**
   * @test
   */
  public function givenExistingClients_whenGetPaginatedListByCriteria_shouldReturnDtoPaginatedList(): void {
    // given
    $criteria = (new ClientPaginatedListCriteria(ClientPaginatedListFilter::class))
        ->setFilter((new ClientPaginatedListFilter())->setSearch('search'))
        ->setSort((new Sort())->setBy('by')->setOrder(Order::DESC));

    $client1 = $this->client(1);
    $client2 = $this->client(2);

    $entityPage = EntityPage::of(Client::class, $client1, $client2);

    $this->clientRepository
        ->expects(static::once())
        ->method('findAllByCriteria')
        ->with($criteria)
        ->willReturn($entityPage);

    // when
    $paginatedList = $this->clientService->getPaginatedListByCriteria($criteria);

    // then
    Assert::assertNotEmpty($paginatedList->getItems());
    Assert::assertCount(2, $paginatedList->getItems());
    Assert::assertEquals(
        [
            ClientListItemDtoMapper::map($client1),
            ClientListItemDtoMapper::map($client2)],
        $paginatedList->getItems());
    Assert::assertEquals($paginatedList->getSort(), $criteria->getSort());
    Assert::assertEquals(
        PageMapper::map(
            Client::class,
            ClientPaginatedListFilter::class,
            (new CriteriaWithEntityPageWrapper(
                Client::class,
                ClientPaginatedListFilter::class))
                ->setEntityPage($entityPage)
                ->setCriteria($criteria)),
        $paginatedList->getPage());
  }

  /**
   * @test
   */
  public function givenNonExistingClient_whenGetDetails_shouldReturnNull(): void {
    // given
    $id = 123;

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $dto = $this->clientService->getDetails($id);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenExistingClient_whenGetDetails_shouldReturnDto(): void {
    // given
    $id = 123;
    $client = $this->client($id);

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($client);

    // when
    $dto = $this->clientService->getDetails($id);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals(ClientDetailsDtoMapper::map($client), $dto);
  }

  /**
   * @test
   */
  public function givenValid_whenCreate_shouldSave(): void {
    // given
    $request = (new ClientCreateRequest())
        ->setFirstname('firstname')
        ->setSurname('surname')
        ->setForeignId('foreignId');

    $this->clientRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            Assert::callback(
                fn (Client $client) => $client->getFirstname() === $request->getFirstname()
                    && $client->getSurname() === $request->getSurname()
                    && $client->getForeignId() === $request->getForeignId()));

    // when
    $this->clientService->create($request);

    // then
  }

  /**
   * @test
   */
  public function givenNonExistingClient_whenGetUpdateRequest_shouldReturnNull(): void {
    // given
    $id = 123;

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $dto = $this->clientService->getUpdateRequest($id);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenExistingClient_whenGetUpdateRequest_shouldReturnDto(): void {
    // given
    $id = 123;
    $client = $this->client($id);

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($client);

    // when
    $dto = $this->clientService->getUpdateRequest($id);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals(ClientUpdateRequestMapper::map($client), $dto);
  }

  /**
   * @test
   */
  public function givenNonExistingClient_whenUpdate_shouldReturnThrowException(): void {
    $this->expectException(EntityNotFoundException::class);
    $this->expectExceptionMessage('Client not found');

    // given
    $id = 123;
    $request = new ClientUpdateRequest();

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $this->clientService->update($id, $request);

    // then
  }

  /**
   * @test
   */
  public function givenExistingClient_whenUpdate_shouldReturnSave(): void {
    // given
    $id = 123;
    $client = $this->client($id);
    $request = (new ClientUpdateRequest())
        ->setFirstname('newFirstname')
        ->setSurname('newSurname')
        ->setForeignId('newForeignId');

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($client);

    $this->clientRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            Assert::callback(
                fn (Client $client) => $client->getId() === $id
                    && $client->getFirstname() === $request->getFirstname()
                    && $client->getSurname() === $request->getSurname()
                    && $client->getForeignId() === $request->getForeignId()));

    // when
    $this->clientService->update($id, $request);

    // then
  }

  /**
   * @test
   */
  public function givenNonExistingClient_whenDelete_shouldThrowException(): void {
    $this->expectException(EntityNotFoundException::class);
    $this->expectExceptionMessage('Client not found');

    // given
    $id = 123;

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $this->clientService->delete($id);

    // then
  }

  /**
   * @test
   */
  public function givenExistingClient_whenDelete_shouldSoftDelete(): void {
    // given
    $id = 123;
    $specialist = $this->client($id);

    $this->clientRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($specialist);

    $this->clientRepository
        ->expects(static::once())
        ->method('softDeleteById')
        ->with($id);

    // when
    $this->clientService->delete($id);

    // then
  }

  private function client(int $id): Client {
    return (new Client())
        ->setId($id)
        ->setFirstname('firstname#' . $id)
        ->setSurname('surname#' . $id)
        ->setForeignId('foreignId#' . $id);
  }
}