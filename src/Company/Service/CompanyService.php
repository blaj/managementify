<?php

namespace App\Company\Service;

use App\Common\Entity\Address;
use App\Company\Dto\CompanyDetailsDto;
use App\Company\Dto\CompanyUpdateRequest;
use App\Company\Mapper\CompanyDetailsDtoMapper;
use App\Company\Mapper\CompanyUpdateRequestMapper;
use App\Company\Repository\CompanyRepository;

class CompanyService {

  public function __construct(
      private readonly CompanyRepository $companyRepository,
      private readonly CompanyFetchService $companyFetchService) {}

  public function getDetails(int $id): ?CompanyDetailsDto {
    return CompanyDetailsDtoMapper::map($this->companyRepository->findOneById($id));
  }

  public function getUpdateRequest(int $id): ?CompanyUpdateRequest {
    return CompanyUpdateRequestMapper::map($this->companyRepository->findOneById($id));
  }

  public function update(int $id, CompanyUpdateRequest $companyUpdateRequest): void {
    $company = $this->companyFetchService->fetchCompany($id)
        ->setName($companyUpdateRequest->getName())
        ->setAddress(
            (new Address())
                ->setCity($companyUpdateRequest->getCity())
                ->setStreet($companyUpdateRequest->getStreet())
                ->setPostcode($companyUpdateRequest->getPostcode()));

    $this->companyRepository->save($company);
  }
}