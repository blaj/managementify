<?php

namespace App\Tests\Specialist\Service;

use App\Common\Dto\EntityPage;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\Sort;
use App\Common\PaginatedList\Mapper\PageMapper;
use App\Specialist\Dto\SpecialistCreateRequest;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Dto\SpecialistUpdateRequest;
use App\Specialist\Entity\Specialist;
use App\Specialist\Mapper\SpecialistDetailsDtoMapper;
use App\Specialist\Mapper\SpecialistListItemDtoMapper;
use App\Specialist\Mapper\SpecialistUpdateRequestMapper;
use App\Specialist\Repository\SpecialistRepository;
use App\Specialist\Service\SpecialistService;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SpecialistServiceTest extends TestCase {

  private SpecialistRepository $specialistRepository;

  private SpecialistService $specialistService;

  public function setUp(): void {
    $this->specialistRepository = $this->createMock(SpecialistRepository::class);

    $this->specialistService = new SpecialistService($this->specialistRepository);
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialists_whenGetPaginatedListByCriteria_shouldReturnEmptyPaginatedList(): void {
    // given
    $criteria = new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class);

    $this->specialistRepository
        ->expects(static::once())
        ->method('findAllByCriteria')
        ->with($criteria)
        ->willReturn(EntityPage::empty(Specialist::class));

    // when
    $paginatedList = $this->specialistService->getPaginatedListByCriteria($criteria);

    // then
    Assert::assertEmpty($paginatedList->getItems());
  }

  /**
   * @test
   */
  public function givenExistingSpecialists_whenGetPaginatedListByCriteria_shouldReturnDtoPaginatedList(): void {
    // given
    $criteria = (new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class))
        ->setFilter((new SpecialistPaginatedListFilter())->setSearch('search'))
        ->setSort((new Sort())->setBy('by')->setOrder(Order::DESC));

    $specialist1 = $this->specialist(1);
    $specialist2 = $this->specialist(2);

    $entityPage = EntityPage::of(Specialist::class, $specialist1, $specialist2);

    $this->specialistRepository
        ->expects(static::once())
        ->method('findAllByCriteria')
        ->with($criteria)
        ->willReturn($entityPage);

    // when
    $paginatedList = $this->specialistService->getPaginatedListByCriteria($criteria);

    // then
    Assert::assertNotEmpty($paginatedList->getItems());
    Assert::assertCount(2, $paginatedList->getItems());
    Assert::assertEquals(
        [
            SpecialistListItemDtoMapper::map($specialist1),
            SpecialistListItemDtoMapper::map($specialist2)],
        $paginatedList->getItems());
    Assert::assertEquals($paginatedList->getSort(), $criteria->getSort());
    Assert::assertEquals(
        PageMapper::map(
            Specialist::class,
            SpecialistPaginatedListFilter::class,
            (new CriteriaWithEntityPageWrapper(
                Specialist::class,
                SpecialistPaginatedListFilter::class))
                ->setEntityPage($entityPage)
                ->setCriteria($criteria)),
        $paginatedList->getPage());
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialist_whenGetDetails_shouldReturnNull(): void {
    // given
    $id = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $dto = $this->specialistService->getDetails($id);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenExistingSpecialist_whenGetDetails_shouldReturnDto(): void {
    // given
    $id = 123;
    $specialist = $this->specialist($id);

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($specialist);

    // when
    $dto = $this->specialistService->getDetails($id);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals(SpecialistDetailsDtoMapper::map($specialist), $dto);
  }

  /**
   * @test
   */
  public function givenValid_whenCreate_shouldSave(): void {
    // given
    $request =
        (new SpecialistCreateRequest())
            ->setFirstname('firstname')
            ->setSurname('surname')
            ->setForeignId('foreignId');

    $this->specialistRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            Assert::callback(
                fn (Specialist $specialist) => $specialist->getFirstname()
                    === $request->getFirstname()
                    && $specialist->getSurname() === $request->getSurname()
                    && $specialist->getForeignId() === $request->getForeignId()));

    // when
    $this->specialistService->create($request);

    // then
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialist_whenGetUpdateRequest_shouldReturnNull(): void {
    // given
    $id = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $dto = $this->specialistService->getUpdateRequest($id);

    // then
    Assert::assertNull($dto);
  }

  /**
   * @test
   */
  public function givenExistingSpecialist_whenGetUpdateRequest_shouldReturnDto(): void {
    // given
    $id = 123;
    $specialist = $this->specialist($id);

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($specialist);

    // when
    $dto = $this->specialistService->getUpdateRequest($id);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals(SpecialistUpdateRequestMapper::map($specialist), $dto);
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialist_whenUpdate_shouldThrowException(): void {
    $this->expectException(EntityNotFoundException::class);
    $this->expectExceptionMessage('Specialist not found');

    // given
    $id = 123;
    $request = new SpecialistUpdateRequest();

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $this->specialistService->update($id, $request);

    // then
  }

  /**
   * @test
   */
  public function givenExistingSpecialist_whenUpdate_shouldSave(): void {
    // given
    $id = 123;
    $specialist = $this->specialist($id);
    $request =
        (new SpecialistUpdateRequest())
            ->setFirstname('newFirstname')
            ->setSurname('newSurname')
            ->setForeignId('newForeignId');

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($specialist);

    $this->specialistRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            Assert::callback(
                fn (Specialist $specialist) => $specialist->getId() === $id
                    && $specialist->getFirstname() === $request->getFirstname()
                    && $specialist->getSurname() === $request->getSurname()
                    && $specialist->getForeignId() === $request->getForeignId()));

    // when
    $this->specialistService->update($id, $request);

    // then
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialist_whenDelete_shouldThrowException(): void {
    $this->expectException(EntityNotFoundException::class);
    $this->expectExceptionMessage('Specialist not found');

    // given
    $id = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $this->specialistService->delete($id);

    // then
  }

  /**
   * @test
   */
  public function givenExistingSpecialist_whenDelete_shouldSoftDelete(): void {
    // given
    $id = 123;
    $specialist = $this->specialist($id);

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($specialist);

    $this->specialistRepository
        ->expects(static::once())
        ->method('softDeleteById')
        ->with($id);

    // when
    $this->specialistService->delete($id);

    // then
  }

  private function specialist(int $id): Specialist {
    return (new Specialist())
        ->setId($id)
        ->setFirstname('firstname#' . $id)
        ->setSurname('surname#' . $id)
        ->setForeignId('foreignId#' . $id);
  }
}