<?php

namespace App\Tests\Specialist\Repository;

use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\PageCriteria;
use App\Common\PaginatedList\Dto\Sort;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Entity\Specialist;
use App\Specialist\Repository\SpecialistRepository;
use App\Tests\RepositoryTestCase;
use PHPUnit\Framework\Assert;

class SpecialistRepositoryTest extends RepositoryTestCase {

  private SpecialistRepository $specialistRepository;

  public function setUp(): void {
    parent::setUp();

    $this->specialistRepository = self::getService(SpecialistRepository::class);
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
  public function givenNonExistingCriteriaSearch_whenFindAllByCriteria_shouldReturnEmptyEntityPage(): void {
    // given
    $specialist = $this->specialist(1);

    $this->specialistRepository->save($specialist);

    $criteria = (new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class))
        ->setPageCriteria(PageCriteria::default())
        ->setSort((new Sort())->setBy('specialist.id')->setOrder(Order::ASC))
        ->setFilter((new SpecialistPaginatedListFilter())->setSearch('nonExisting'));

    // when
    $entityPage = $this->specialistRepository->findAllByCriteria($criteria);

    // then
    Assert::assertEmpty($entityPage->getItems());
    Assert::assertEquals(0, $entityPage->getTotalItems());
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
    $entityPage = $this->specialistRepository->findAllByCriteria($criteria);

    // then
    Assert::assertNotEmpty($entityPage->getItems());
    Assert::assertEquals(2, $entityPage->getTotalItems());
    Assert::assertEquals([$specialist1, $specialist2], $entityPage->getItems());
  }

  private function specialist(int $iterator): Specialist {
    return (new Specialist())
        ->setFirstname('firstname#' . $iterator)
        ->setSurname('surname#' . $iterator)
        ->setForeignId('foreignId#' . $iterator);
  }
}