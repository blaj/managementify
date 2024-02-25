<?php

namespace App\ClientSpecialist\Service;

use App\Client\Service\ClientFetchService;
use App\ClientSpecialist\Dto\ClientSpecialistCreateRequest;
use App\ClientSpecialist\Dto\ClientSpecialistListItemDto;
use App\ClientSpecialist\Dto\ClientSpecialistUpdateRequest;
use App\ClientSpecialist\Entity\ClientSpecialist;
use App\ClientSpecialist\Mapper\ClientSpecialistListItemDtoMapper;
use App\ClientSpecialist\Mapper\ClientSpecialistUpdateRequestMapper;
use App\ClientSpecialist\Repository\ClientSpecialistRepository;
use App\Company\Service\CompanyFetchService;
use App\Specialist\Service\SpecialistFetchService;
use Doctrine\ORM\EntityNotFoundException;

class ClientSpecialistService {

  public function __construct(
      private readonly ClientSpecialistRepository $clientSpecialistRepository,
      private readonly ClientFetchService $clientFetchService,
      private readonly SpecialistFetchService $specialistFetchService,
      private readonly CompanyFetchService $companyFetchService) {}

  /**
   * @return array<ClientSpecialistListItemDto>
   */
  public function getListForClient(int $clientId, int $companyId): array {
    return array_filter(
        array_map(
            fn (?ClientSpecialist $clientSpecialist) => ClientSpecialistListItemDtoMapper::map(
                $clientSpecialist),
            $this->clientSpecialistRepository->findAllByClientIdAndCompanyId(
                $clientId,
                $companyId)),
        fn (?ClientSpecialistListItemDto $dto) => $dto !== null);
  }

  public function createForClient(
      int $clientId,
      ClientSpecialistCreateRequest $clientSpecialistCreateRequest,
      int $companyId): void {
    $clientSpecialist =
        (new ClientSpecialist())
            ->setFromDate($clientSpecialistCreateRequest->getFromDate())
            ->setToDate($clientSpecialistCreateRequest->getToDate())
            ->setAssignType($clientSpecialistCreateRequest->getAssignType())
            ->setClient($this->clientFetchService->fetchClient($clientId, $companyId))
            ->setSpecialist(
                $this->specialistFetchService->fetchSpecialist(
                    $clientSpecialistCreateRequest->getSpecialistId(),
                    $companyId))
            ->setCompany($this->companyFetchService->fetchCompany($companyId));

    $this->clientSpecialistRepository->save($clientSpecialist);
  }

  public function getUpdateRequest(int $id, int $companyId): ?ClientSpecialistUpdateRequest {
    return ClientSpecialistUpdateRequestMapper::map(
        $this->clientSpecialistRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function update(
      int $id,
      ClientSpecialistUpdateRequest $clientSpecialistUpdateRequest,
      int $companyId): void {
    $this->clientSpecialistRepository->save(
        ($this->fetchClientSpecialist($id, $companyId))
            ->setFromDate($clientSpecialistUpdateRequest->getFromDate())
            ->setToDate($clientSpecialistUpdateRequest->getToDate())
            ->setAssignType($clientSpecialistUpdateRequest->getAssignType()));
  }

  public function delete(int $id, int $companyId): void {
    $this->clientSpecialistRepository->softDeleteById(
        $this->fetchClientSpecialist($id, $companyId)->getId());
  }

  private function fetchClientSpecialist(int $id, int $companyId): ClientSpecialist {
    return $this->clientSpecialistRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('Client specialist not found');
  }
}