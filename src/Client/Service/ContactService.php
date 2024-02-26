<?php

namespace App\Client\Service;

use App\Client\Dto\ContactCreateRequest;
use App\Client\Dto\ContactListItemDto;
use App\Client\Dto\ContactUpdateRequest;
use App\Client\Entity\Contact;
use App\Client\Mapper\ContactListItemDtoMapper;
use App\Client\Mapper\ContactUpdateRequestMapper;
use App\Client\Repository\ContactRepository;
use App\Company\Service\CompanyFetchService;
use Doctrine\ORM\EntityNotFoundException;

class ContactService {

  public function __construct(
      private readonly ContactRepository $contactRepository,
      private readonly ClientFetchService $clientFetchService,
      private readonly CompanyFetchService $companyFetchService) {}

  /**
   * @return array<ContactListItemDto>
   */
  public function getList(int $clientId, int $companyId): array {
    return array_filter(
        array_map(
            fn (?Contact $contact) => ContactListItemDtoMapper::map($contact),
            $this->contactRepository->findAllByClientIdAndCompanyId($clientId, $companyId)),
        fn (?ContactListItemDto $dto) => $dto !== null);
  }

  public function create(
      int $clientId,
      ContactCreateRequest $contactCreateRequest,
      int $companyId): void {
    $contact = (new Contact())
        ->setContent($contactCreateRequest->getContent())
        ->setType($contactCreateRequest->getType())
        ->setClient($this->clientFetchService->fetchClient($clientId, $companyId))
        ->setCompany($this->companyFetchService->fetchCompany($companyId));

    $this->contactRepository->save($contact);
  }

  public function getUpdateRequest(int $id, int $companyId): ?ContactUpdateRequest {
    return ContactUpdateRequestMapper::map(
        $this->contactRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function update(
      int $id,
      ContactUpdateRequest $contactUpdateRequest,
      int $companyId): void {
    $contact =
        $this->fetchContact($id, $companyId)
            ->setContent($contactUpdateRequest->getContent())
            ->setType($contactUpdateRequest->getType());

    $this->contactRepository->save($contact);
  }

  public function delete(int $id, int $companyId): void {
    $this->contactRepository->softDeleteById($this->fetchContact($id, $companyId)->getId());
  }

  private function fetchContact(int $id, int $companyId): Contact {
    return $this->contactRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('Contact not found');
  }
}