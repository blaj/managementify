<?php

namespace App\Tests\Client\Repository;

use App\Client\Dto\ClientPaginatedListCriteria;
use App\Client\Dto\ClientPaginatedListFilter;
use App\Client\Entity\Client;
use App\Client\Repository\ClientRepository;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Common\PaginatedList\Dto\Sort;
use App\Tests\RepositoryTestCase;
use PHPUnit\Framework\Assert;

class ClientRepositoryTest extends RepositoryTestCase {

  private ClientRepository $clientRepository;

  public function setUp(): void {
    parent::setUp();

    $this->clientRepository = self::getService(ClientRepository::class);
  }

  /**
   * @test
   */
  public function givenNonExistingId_whenFindOneById_shouldReturnNull(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    $nonExistingId = $client->getId() + 1;

    // when
    $entity = $this->clientRepository->findOneById($nonExistingId);

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenValid_whenFindOneById_shouldReturnEntity(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    $existingId = $client->getId();

    // when
    $entity = $this->clientRepository->findOneById($existingId);

    // then
    Assert::assertNotNull($entity);
    Assert::assertEquals($entity, $client);
  }

  /**
   * @test
   */
  public function givenNonExistingCriteriaSearch_whenFindAllByCriteria_shouldReturnEmptyEntityPage(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    $criteria = (new ClientPaginatedListCriteria(ClientPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('client.id')->setOrder(Order::ASC))
        ->setFilter((new ClientPaginatedListFilter())->setSearch('nonExisting'));

    // when
    $entityPage = $this->clientRepository->findAllByCriteria($criteria);

    // then
    Assert::assertEmpty($entityPage->getItems());
    Assert::assertEquals(0, $entityPage->getTotalItems());
  }

  /**
   * @test
   */
  public function givenValid_whenFindAllByCriteria_shouldReturnEntityPage(): void {
    // given
    $client1 = $this->client(1);
    $client2 = $this->client(2);

    $this->clientRepository->save($client1);
    $this->clientRepository->save($client2);

    $criteria = (new ClientPaginatedListCriteria(ClientPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('client.id')->setOrder(Order::ASC))
        ->setFilter((new ClientPaginatedListFilter())->setSearch('firstName'));

    // when
    $entityPage = $this->clientRepository->findAllByCriteria($criteria);

    // then
    Assert::assertNotEmpty($entityPage->getItems());
    Assert::assertEquals(2, $entityPage->getTotalItems());
    Assert::assertEquals([$client1, $client2], $entityPage->getItems());
  }

  private function client(int $iterator): Client {
    return (new Client())
        ->setFirstname('firstname#' . $iterator)
        ->setSurname('surname#' . $iterator)
        ->setForeignId('foreignId#' . $iterator);
  }
}