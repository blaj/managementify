<?php

namespace App\Tests\Specialist\Service;

use App\Common\Dto\EntityPage;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\Order;
use App\Common\PaginatedList\Dto\Sort;
use App\Common\PaginatedList\Mapper\PageMapper;
use App\Company\Entity\Company;
use App\Company\Service\CompanyFetchService;
use App\Specialist\Dto\SpecialistCreateRequest;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Dto\SpecialistUpdateRequest;
use App\Specialist\Entity\Specialist;
use App\Specialist\Mapper\SpecialistDetailsDtoMapper;
use App\Specialist\Mapper\SpecialistListItemDtoMapper;
use App\Specialist\Mapper\SpecialistUpdateRequestMapper;
use App\Specialist\Repository\SpecialistRepository;
use App\Specialist\Service\SpecialistFetchService;
use App\Specialist\Service\SpecialistService;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class SpecialistServiceTest extends TestCase {

  private SpecialistRepository $specialistRepository;
  private SpecialistFetchService $specialistFetchService;
  private CompanyFetchService $companyFetchService;

  private SpecialistService $specialistService;

  public function setUp(): void {
    $this->specialistRepository = $this->createMock(SpecialistRepository::class);
    $this->specialistFetchService = $this->createMock(SpecialistFetchService::class);
    $this->companyFetchService = $this->createMock(CompanyFetchService::class);

    $this->specialistService =
        new SpecialistService(
            $this->specialistRepository,
            $this->specialistFetchService,
            $this->companyFetchService);
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialists_whenGetPaginatedListByCriteria_shouldReturnEmptyPaginatedList(): void {
    // given
    $criteria = new SpecialistPaginatedListCriteria(SpecialistPaginatedListFilter::class);
    $companyId = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findAllByCriteria')
        ->with($criteria, $companyId)
        ->willReturn(EntityPage::empty(Specialist::class));

    // when
    $paginatedList = $this->specialistService->getPaginatedListByCriteria($criteria, $companyId);

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
    $companyId = 123;

    $specialist1 = $this->specialist(1);
    $specialist2 = $this->specialist(2);

    $entityPage = EntityPage::of(Specialist::class, $specialist1, $specialist2);

    $this->specialistRepository
        ->expects(static::once())
        ->method('findAllByCriteria')
        ->with($criteria, $companyId)
        ->willReturn($entityPage);

    // when
    $paginatedList = $this->specialistService->getPaginatedListByCriteria($criteria, $companyId);

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
    $companyId = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneByIdAndCompany')
        ->with($id, $companyId)
        ->willReturn(null);

    // when
    $dto = $this->specialistService->getDetails($id, $companyId);

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
    $companyId = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneByIdAndCompany')
        ->with($id, $companyId)
        ->willReturn($specialist);

    // when
    $dto = $this->specialistService->getDetails($id, $companyId);

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
    $companyId = 123;

    $this->companyFetchService
        ->expects(static::once())
        ->method('fetchCompany')
        ->with($companyId)
        ->willReturn((new Company())->setId($companyId));

    $this->specialistRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            Assert::callback(
                fn (Specialist $specialist) => $specialist->getFirstname()
                    === $request->getFirstname()
                    && $specialist->getSurname() === $request->getSurname()
                    && $specialist->getForeignId() === $request->getForeignId()
                    && $specialist->getCompany()->getId() === $companyId));

    // when
    $this->specialistService->create($request, $companyId);

    // then
  }

  /**
   * @test
   */
  public function givenNonExistingSpecialist_whenGetUpdateRequest_shouldReturnNull(): void {
    // given
    $id = 123;
    $companyId = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneByIdAndCompany')
        ->with($id, $companyId)
        ->willReturn(null);

    // when
    $dto = $this->specialistService->getUpdateRequest($id, $companyId);

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
    $companyId = 123;

    $this->specialistRepository
        ->expects(static::once())
        ->method('findOneByIdAndCompany')
        ->with($id, $companyId)
        ->willReturn($specialist);

    // when
    $dto = $this->specialistService->getUpdateRequest($id, $companyId);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals(SpecialistUpdateRequestMapper::map($specialist), $dto);
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
    $companyId = 123;

    $this->specialistFetchService
        ->expects(static::once())
        ->method('fetchSpecialist')
        ->with($id, $companyId)
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
    $this->specialistService->update($id, $request, $companyId);

    // then
  }

  /**
   * @test
   */
  public function givenExistingSpecialist_whenDelete_shouldSoftDelete(): void {
    // given
    $id = 123;
    $specialist = $this->specialist($id);
    $companyId = 123;

    $this->specialistFetchService
        ->expects(static::once())
        ->method('fetchSpecialist')
        ->with($id, $companyId)
        ->willReturn($specialist);

    $this->specialistRepository
        ->expects(static::once())
        ->method('softDeleteById')
        ->with($id);

    // when
    $this->specialistService->delete($id, $companyId);

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