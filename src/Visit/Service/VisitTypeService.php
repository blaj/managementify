<?php

namespace App\Visit\Service;

use App\Company\Service\CompanyFetchService;
use App\Visit\Dto\VisitTypeCreateRequest;
use App\Visit\Dto\VisitTypeDetailsDto;
use App\Visit\Dto\VisitTypeListItemDto;
use App\Visit\Dto\VisitTypeUpdateRequest;
use App\Visit\Entity\VisitType;
use App\Visit\Mapper\VisitTypeDetailsDtoMapper;
use App\Visit\Mapper\VisitTypeListItemDtoMapper;
use App\Visit\Mapper\VisitTypeUpdateRequestMapper;
use App\Visit\Repository\VisitTypeRepository;
use Doctrine\ORM\EntityNotFoundException;

class VisitTypeService {

  public function __construct(
      private readonly VisitTypeRepository $visitTypeRepository,
      private readonly CompanyFetchService $companyFetchService,
      private readonly VisitTypeFetchService $visitTypeFetchService) {}

  /**
   * @return array<VisitTypeListItemDto>
   */
  public function getList(int $companyId): array {
    return array_filter(
        array_map(
            fn (?VisitType $visitType) => VisitTypeListItemDtoMapper::map($visitType),
            $this->visitTypeRepository->findAllByCompanyId($companyId)),
        fn (?VisitTypeListItemDto $dto) => $dto !== null);
  }

  public function getDetails(int $id, int $companyId): ?VisitTypeDetailsDto {
    return VisitTypeDetailsDtoMapper::map(
        $this->visitTypeRepository->findOneByIdAndCompanyId($id, $companyId));
  }

  public function create(VisitTypeCreateRequest $visitTypeCreateRequest, int $companyId): void {
    $visitType = new VisitType();
    $visitType->setPreferredPrice($visitTypeCreateRequest->getPreferredPrice());
    $visitType->setCompany($this->companyFetchService->fetchCompany($companyId));
    $visitType->setCode($visitTypeCreateRequest->getCode());
    $visitType->setName($visitTypeCreateRequest->getName());

    $this->visitTypeRepository->save($visitType);
  }

  public function getUpdateRequest(int $id, int $companyId): ?VisitTypeUpdateRequest {
    return VisitTypeUpdateRequestMapper::map(
        $this->visitTypeRepository->findOneByIdAndCompanyId($id, $companyId));
  }

  public function update(
      int $id,
      VisitTypeUpdateRequest $visitTypeUpdateRequest,
      int $companyId): void {
    $visitType = $this->visitTypeFetchService->fetchVisitType($id, $companyId);
    $visitType
        ->setPreferredPrice($visitTypeUpdateRequest->getPreferredPrice())
        ->setName($visitTypeUpdateRequest->getName());

    $this->visitTypeRepository->save($visitType);
  }

  public function archive(int $id, int $companyId): void {
    $this->visitTypeRepository->archiveById(
        $this->visitTypeFetchService->fetchVisitType($id, $companyId)->getId());
  }

  public function unArchive(int $id, int $companyId): void {
    $this->visitTypeRepository->unArchiveById(
        $this->visitTypeFetchService->fetchVisitType($id, $companyId)->getId());
  }
}