<?php

namespace App\Tests\Specialist\Repository;

use App\Common\Entity\Address;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Common\PaginatedList\Dto\Sort;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Entity\Specialist;
use App\Specialist\Repository\SpecialistRepository;
use App\Tests\RepositoryTestCase;
use PHPUnit\Framework\Assert;

class SpecialistRepositoryTest extends RepositoryTestCase {

  private SpecialistRepository $specialistRepository;
  private CompanyRepository $companyRepository;

  private Company $company;

  public function setUp(): void {
    parent::setUp();

    $this->specialistRepository = self::getService(SpecialistRepository::class);
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
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $nonExistingId = $specialist->getId() + 1;

    // when
    $entity = $this->specialistRepository->findOneById($nonExistingId);

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenValid_whenFindOneById_shouldReturnEntity(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $existingId = $specialist->getId();

    // when
    $entity = $this->specialistRepository->findOneById($existingId);

    // then
    Assert::assertNotNull($entity);
    Assert::assertEquals($entity, $specialist);
  }

  /**
   * @test
   */
  public function givenNonExistingId_whenFindOneByIdAndCompanyId_shouldReturnNull(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $nonExistingId = $specialist->getId() + 1;

    // when
    $entity =
        $this->specialistRepository->findOneByIdAndCompany($nonExistingId, $this->company->getId());

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindOneByIdAndCompanyId_shouldReturnNull(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $nonExistingCompanyId = $this->company->getId() + 1;

    // when
    $entity =
        $this->specialistRepository->findOneByIdAndCompany(
            $specialist->getId(),
            $nonExistingCompanyId);

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenValid_whenFindOneByIdAndCompanyId_shouldReturnEntity(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    // when
    $entity =
        $this->specialistRepository->findOneByIdAndCompany(
            $specialist->getId(),
            $this->company->getId());

    // then
    Assert::assertNotNull($entity);
    Assert::assertEquals($entity, $specialist);
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindAllByCompanyId_shouldReturnEmptyArray(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $nonExistingCompanyId = $this->company->getId() + 1;

    // when
    $entities = $this->specialistRepository->findAllByCompanyId($nonExistingCompanyId);

    // then
    Assert::assertEmpty($entities);
  }

  /**
   * @test
   */
  public function givenValid_whenFindAllByCompanyId_shouldReturnEntities(): void {
    // given
    $specialist1 = $this->specialist(1);
    $specialist2 = $this->specialist(2);

    $this->specialistRepository->save($specialist1);
    $this->specialistRepository->save($specialist2);

    // when
    $entities = $this->specialistRepository->findAllByCompanyId($this->company->getId());

    // then
    Assert::assertNotEmpty($entities);
    Assert::assertCount(2, $entities);
    Assert::assertEquals([$specialist1, $specialist2], $entities);
  }

  /**
   * @test
   */
  public function givenNonExistingCriteriaSearch_whenFindAllByCriteria_shouldReturnEmptyEntityPage(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $criteria = (new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('specialist.id')->setOrder(Order::ASC))
        ->setFilter((new SpecialistPaginatedListFilter())->setSearch('nonExisting'));

    // when
    $entityPage =
        $this->specialistRepository->findAllByCriteria($criteria, $this->company->getId());

    // then
    Assert::assertEmpty($entityPage->getItems());
    Assert::assertEquals(0, $entityPage->getTotalItems());
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindAllByCriteria_shouldReturnEntityPage(): void {
    // given
    $specialist1 = $this->specialist(1);
    $specialist2 = $this->specialist(2);

    $this->specialistRepository->save($specialist1);
    $this->specialistRepository->save($specialist2);

    $criteria = (new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('specialist.id')->setOrder(Order::ASC))
        ->setFilter((new SpecialistPaginatedListFilter())->setSearch('firstName'));

    $nonExistingCompanyId = $this->company->getId() + 1;

    // when
    $entityPage =
        $this->specialistRepository->findAllByCriteria($criteria, $nonExistingCompanyId);

    // then
    Assert::assertEmpty($entityPage->getItems());
  }

  /**
   * @test
   */
  public function givenValid_whenFindAllByCriteria_shouldReturnEntityPage(): void {
    // given
    $specialist1 = $this->specialist(1);
    $specialist2 = $this->specialist(2);

    $this->specialistRepository->save($specialist1);
    $this->specialistRepository->save($specialist2);

    $criteria = (new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('specialist.id')->setOrder(Order::ASC))
        ->setFilter((new SpecialistPaginatedListFilter())->setSearch('firstName'));

    // when
    $entityPage =
        $this->specialistRepository->findAllByCriteria($criteria, $this->company->getId());

    // then
    Assert::assertNotEmpty($entityPage->getItems());
    Assert::assertEquals(2, $entityPage->getTotalItems());
    Assert::assertEquals([$specialist1, $specialist2], $entityPage->getItems());
  }

  private function specialist(int $iterator): Specialist {
    return (new Specialist())
        ->setFirstname('firstname#' . $iterator)
        ->setSurname('surname#' . $iterator)
        ->setForeignId('foreignId#' . $iterator)
        ->setCompany($this->company);
  }
}