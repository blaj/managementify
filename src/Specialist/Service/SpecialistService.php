<?php

namespace App\Specialist\Service;

use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\PaginatedList;
use App\Common\PaginatedList\Mapper\PageMapper;
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

  public function __construct(private readonly SpecialistRepository $specialistRepository) {}

  /**
   * @return PaginatedList<SpecialistListItemDto>
   */
  public function getPaginatedListByCriteria(
      SpecialistPaginatedListCriteria $specialistPaginatedListCriteria): PaginatedList {
    $specialistsPage =
        $this->specialistRepository->findAllByCriteria($specialistPaginatedListCriteria);

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

  public function getDetails(int $id): ?SpecialistDetailsDto {
    return SpecialistDetailsDtoMapper::map($this->specialistRepository->findOneById($id));
  }

  public function create(SpecialistCreateRequest $specialistCreateRequest): void {
    $specialist = (new Specialist())
        ->setFirstname($specialistCreateRequest->getFirstname())
        ->setSurname($specialistCreateRequest->getSurname())
        ->setForeignId($specialistCreateRequest->getForeignId());

    $this->specialistRepository->save($specialist);
  }

  public function getUpdateRequest(int $id): ?SpecialistUpdateRequest {
    return SpecialistUpdateRequestMapper::map($this->specialistRepository->findOneById($id));
  }

  public function update(int $id, SpecialistUpdateRequest $specialistUpdateRequest): void {
    $this->specialistRepository->save(
        ($this->fetchSpecialist($id))
            ->setFirstname($specialistUpdateRequest->getFirstname())
            ->setSurname($specialistUpdateRequest->getSurname())
            ->setForeignId($specialistUpdateRequest->getForeignId()));
  }

  public function delete(int $id): void {
    $this->specialistRepository->softDeleteById($this->fetchSpecialist($id)->getId());
  }

  private function fetchSpecialist(int $id): Specialist {
    return $this->specialistRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Specialist not found');
  }
}