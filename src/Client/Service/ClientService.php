<?php

namespace App\Client\Service;

use App\Client\Dto\ClientCreateRequest;
use App\Client\Dto\ClientDetailsDto;
use App\Client\Dto\ClientListItemDto;
use App\Client\Dto\ClientPaginatedListCriteria;
use App\Client\Dto\ClientPaginatedListFilter;
use App\Client\Dto\ClientUpdateRequest;
use App\Client\Entity\Client;
use App\Client\Mapper\ClientDetailsDtoMapper;
use App\Client\Mapper\ClientListItemDtoMapper;
use App\Client\Mapper\ClientUpdateRequestMapper;
use App\Client\Repository\ClientRepository;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\PaginatedList;
use App\Common\PaginatedList\Mapper\PageMapper;
use App\Company\Service\CompanyFetchService;
use Doctrine\ORM\EntityNotFoundException;

class ClientService {

  public function __construct(
      private readonly ClientRepository $clientRepository,
      private readonly CompanyFetchService $companyFetchService,
      private readonly ClientFetchService $clientFetchService) {}

  /**
   * @return PaginatedList<ClientListItemDto>
   */
  public function getPaginatedListByCriteria(
      ClientPaginatedListCriteria $clientPaginatedListCriteria,
      int $companyId): PaginatedList {
    $clientsPage =
        $this->clientRepository->findAllByCriteria($clientPaginatedListCriteria, $companyId);

    return (new PaginatedList(ClientListItemDto::class))
        ->setItems(
            array_filter(
                array_map(
                    fn (?Client $client) => ClientListItemDtoMapper::map($client),
                    $clientsPage->getItems()),
                fn (?ClientListItemDto $dto) => $dto !== null))
        ->setPage(
            PageMapper::map(
                Client::class,
                ClientPaginatedListFilter::class,
                (new CriteriaWithEntityPageWrapper(
                    Client::class,
                    ClientPaginatedListFilter::class))
                    ->setEntityPage($clientsPage)
                    ->setCriteria($clientPaginatedListCriteria)))
        ->setSort($clientPaginatedListCriteria->getSort());
  }

  public function getDetails(int $id, int $companyId): ?ClientDetailsDto {
    return ClientDetailsDtoMapper::map(
        $this->clientRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function create(ClientCreateRequest $clientCreateRequest, int $companyId): void {
    $client = (new Client())
        ->setFirstname($clientCreateRequest->getFirstname())
        ->setSurname($clientCreateRequest->getSurname())
        ->setForeignId($clientCreateRequest->getForeignId())
        ->setCompany($this->companyFetchService->fetchCompany($companyId));

    $this->clientRepository->save($client);
  }

  public function getUpdateRequest(int $id, int $companyId): ?ClientUpdateRequest {
    return ClientUpdateRequestMapper::map(
        $this->clientRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function update(int $id, ClientUpdateRequest $clientUpdateRequest, int $companyId): void {
    $this->clientRepository->save(
        ($this->clientFetchService->fetchClient($id, $companyId))
            ->setFirstname($clientUpdateRequest->getFirstname())
            ->setSurname($clientUpdateRequest->getSurname())
            ->setForeignId($clientUpdateRequest->getForeignId()));
  }

  public function delete(int $id, int $companyId): void {
    $this->clientRepository->softDeleteById($this->clientFetchService->fetchClient($id, $companyId)->getId());
  }
}