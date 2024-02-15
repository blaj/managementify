<?php

namespace App\Specialist\Service;

use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\PaginatedList;
use App\Common\PaginatedList\Mapper\PageMapper;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Company\Service\CompanyFetchService;
use App\Specialist\Dto\SpecialistCreateRequest;
use App\Specialist\Dto\SpecialistDetailsDto;
use App\Specialist\Dto\SpecialistListItemDto;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Dto\SpecialistUpdateRequest;
use App\Specialist\Entity\Specialist;
use App\Specialist\Mapper\SpecialistDetailsDtoMapper;
use App\Specialist\Mapper\SpecialistListItemDtoMapper;
use App\Specialist\Mapper\SpecialistUpdateRequestMapper;
use App\Specialist\Repository\SpecialistRepository;
use Doctrine\ORM\EntityNotFoundException;

class SpecialistService {

  public function __construct(
      private readonly SpecialistRepository $specialistRepository,
      private readonly CompanyFetchService $companyFetchService) {}

  /**
   * @return PaginatedList<SpecialistListItemDto>
   */
  public function getPaginatedListByCriteria(
      SpecialistPaginatedListCriteria $specialistPaginatedListCriteria,
      int $companyId): PaginatedList {
    $specialistsPage =
        $this->specialistRepository->findAllByCriteria(
            $specialistPaginatedListCriteria,
            $companyId);

    return (new PaginatedList(SpecialistListItemDto::class))
        ->setItems(
            array_filter(
                array_map(
                    fn (?Specialist $specialist) => SpecialistListItemDtoMapper::map($specialist),
                    $specialistsPage->getItems()),
                fn (?SpecialistListItemDto $dto) => $dto !== null))
        ->setPage(
            PageMapper::map(
                Specialist::class,
                SpecialistPaginatedListFilter::class,
                (new CriteriaWithEntityPageWrapper(
                    Specialist::class,
                    SpecialistPaginatedListFilter::class))
                    ->setEntityPage($specialistsPage)
                    ->setCriteria($specialistPaginatedListCriteria)))
        ->setSort($specialistPaginatedListCriteria->getSort());
  }

  public function getDetails(int $id, int $companyId): ?SpecialistDetailsDto {
    return SpecialistDetailsDtoMapper::map(
        $this->specialistRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function create(SpecialistCreateRequest $specialistCreateRequest, int $companyId): void {
    $specialist = (new Specialist())
        ->setFirstname($specialistCreateRequest->getFirstname())
        ->setSurname($specialistCreateRequest->getSurname())
        ->setForeignId($specialistCreateRequest->getForeignId())
        ->setCompany($this->companyFetchService->fetchCompany($companyId));

    $this->specialistRepository->save($specialist);
  }

  public function getUpdateRequest(int $id, int $companyId): ?SpecialistUpdateRequest {
    return SpecialistUpdateRequestMapper::map(
        $this->specialistRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function update(
      int $id,
      SpecialistUpdateRequest $specialistUpdateRequest,
      int $companyId): void {
    $this->specialistRepository->save(
        ($this->fetchSpecialist($id, $companyId))
            ->setFirstname($specialistUpdateRequest->getFirstname())
            ->setSurname($specialistUpdateRequest->getSurname())
            ->setForeignId($specialistUpdateRequest->getForeignId()));
  }

  public function delete(int $id, int $companyId): void {
    $this->specialistRepository->softDeleteById($this->fetchSpecialist($id, $companyId)->getId());
  }

  private function fetchSpecialist(int $id, int $companyId): Specialist {
    return $this->specialistRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('Specialist not found');
  }
}