<?php

namespace App\Client\Service;

use App\Client\Entity\Client;
use App\Client\Repository\ClientRepository;
use Doctrine\ORM\EntityNotFoundException;

class ClientFetchService {

  public function __construct(private readonly ClientRepository $clientRepository) {}

  public function fetchClient(int $id, int $companyId): Client {
    return $this->clientRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('Client not found');
  }
}