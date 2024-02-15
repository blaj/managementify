<?php

namespace App\Tests\Client\Repository;

use App\Client\Dto\ClientPaginatedListCriteria;
use App\Client\Dto\ClientPaginatedListFilter;
use App\Client\Entity\Client;
use App\Client\Repository\ClientRepository;
use App\Common\Entity\Address;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Common\PaginatedList\Dto\Sort;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Tests\RepositoryTestCase;
use PHPUnit\Framework\Assert;

class ClientRepositoryTest extends RepositoryTestCase {

  private ClientRepository $clientRepository;
  private CompanyRepository $companyRepository;

  private Company $company;

  public function setUp(): void {
    parent::setUp();

    $this->clientRepository = self::getService(ClientRepository::class);
    $this->companyRepository = self::getService(CompanyRepository::class);

    $this->company = (new Company())
        ->setName('name')
        ->setAddress((new Address())->setStreet('street')->setCity('city')->setPostcode('00-00'));
    $this->companyRepository->save($this->company);
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
  public function givenNonExistingId_whenFindOneByIdAndCompanyId_shouldReturnNull(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    $nonExistingId = $client->getId() + 1;

    // when
    $entity =
        $this->clientRepository->findOneByIdAndCompany($nonExistingId, $this->company->getId());

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindOneByIdAndCompanyId_shouldReturnNull(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    $nonExistingCompanyId = $this->company->getId() + 1;

    // when
    $entity =
        $this->clientRepository->findOneByIdAndCompany($client->getId(), $nonExistingCompanyId);

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenValid_whenFindOneByIdAndCompanyId_shouldReturnEntity(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    // when
    $entity =
        $this->clientRepository->findOneByIdAndCompany($client->getId(), $this->company->getId());

    // then
    Assert::assertNotNull($entity);
    Assert::assertEquals($entity, $client);
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindAllByCompanyId_shouldReturnEmptyArray(): void {
    // given
    $client = $this->client(1);

    $this->clientRepository->save($client);

    $nonExistingCompanyId = $this->company->getId() + 1;

    // when
    $entities =
        $this->clientRepository->findAllByCompanyId($nonExistingCompanyId);

    // then
    Assert::assertEmpty($entities);
  }

  /**
   * @test
   */
  public function givenValid_whenFindAllByCompanyId_shouldReturnEntities(): void {
    // given
    $client1 = $this->client(1);
    $client2 = $this->client(1);

    $this->clientRepository->save($client1);
    $this->clientRepository->save($client2);

    // when
    $entities =
        $this->clientRepository->findAllByCompanyId($this->company->getId());

    // then
    Assert::assertNotEmpty($entities);
    Assert::assertCount(2, $entities);
    Assert::assertEquals([$client1, $client2], $entities);
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
    $entityPage = $this->clientRepository->findAllByCriteria($criteria, $this->company->getId());

    // then
    Assert::assertEmpty($entityPage->getItems());
    Assert::assertEquals(0, $entityPage->getTotalItems());
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindAllByCriteria_shouldReturnEntityPage(): void {
    // given
    $client1 = $this->client(1);
    $client2 = $this->client(2);

    $this->clientRepository->save($client1);
    $this->clientRepository->save($client2);

    $criteria = (new ClientPaginatedListCriteria(ClientPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('client.id')->setOrder(Order::ASC))
        ->setFilter((new ClientPaginatedListFilter())->setSearch('firstName'));

    $nonExistingCompanyId = $this->company->getId() + 1;

    // when
    $entityPage = $this->clientRepository->findAllByCriteria($criteria, $nonExistingCompanyId);

    // then
    Assert::assertEmpty($entityPage->getItems());
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
    $entityPage = $this->clientRepository->findAllByCriteria($criteria, $this->company->getId());

    // then
    Assert::assertNotEmpty($entityPage->getItems());
    Assert::assertEquals(2, $entityPage->getTotalItems());
    Assert::assertEquals([$client1, $client2], $entityPage->getItems());
  }

  private function client(int $iterator): Client {
    return (new Client())
        ->setFirstname('firstname#' . $iterator)
        ->setSurname('surname#' . $iterator)
        ->setForeignId('foreignId#' . $iterator)
        ->setCompany($this->company);
  }
}