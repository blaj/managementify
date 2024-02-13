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
use Doctrine\ORM\EntityNotFoundException;

class ClientService {

  public function __construct(private readonly ClientRepository $clientRepository) {}

  /**
   * @return PaginatedList<ClientListItemDto>
   */
  public function getPaginatedListByCriteria(
      ClientPaginatedListCriteria $clientPaginatedListCriteria): PaginatedList {
    $clientsPage = $this->clientRepository->findAllByCriteria($clientPaginatedListCriteria);

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

  public function getDetails(int $id): ?ClientDetailsDto {
    return ClientDetailsDtoMapper::map($this->clientRepository->findOneById($id));
  }

  public function create(ClientCreateRequest $clientCreateRequest): void {
    $client = (new Client())
        ->setFirstname($clientCreateRequest->getFirstname())
        ->setSurname($clientCreateRequest->getSurname())
        ->setForeignId($clientCreateRequest->getForeignId());

    $this->clientRepository->save($client);
  }

  public function getUpdateRequest(int $id): ?ClientUpdateRequest {
    return ClientUpdateRequestMapper::map($this->clientRepository->findOneById($id));
  }

  public function update(int $id, ClientUpdateRequest $clientUpdateRequest): void {
    $this->clientRepository->save(
        ($this->fetchClient($id))
            ->setFirstname($clientUpdateRequest->getFirstname())
            ->setSurname($clientUpdateRequest->getSurname())
            ->setForeignId($clientUpdateRequest->getForeignId()));
  }

  public function delete(int $id): void {
    $this->clientRepository->softDeleteById($this->fetchClient($id)->getId());
  }

  private function fetchClient(int $id): Client {
    return $this->clientRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Client not found');
  }
}