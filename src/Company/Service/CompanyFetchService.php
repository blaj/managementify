<?php

namespace App\Company\Service;

use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use Doctrine\ORM\EntityNotFoundException;

class CompanyFetchService {

  public function __construct(private readonly CompanyRepository $companyRepository) {}

  public function fetchCompany(int $id): Company {
    return $this->companyRepository->findOneById($id)
        ??
        throw new EntityNotFoundException('Company not found');
  }
}