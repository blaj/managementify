<?php

namespace App\Tests\Company\Repository;

use App\Common\Entity\Address;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Tests\RepositoryTestCase;
use PHPUnit\Framework\Assert;

class CompanyRepositoryTest extends RepositoryTestCase {

  private CompanyRepository $companyRepository;

  public function setUp(): void {
    parent::setUp();

    $this->companyRepository = self::getService(CompanyRepository::class);
  }

  /**
   * @test
   */
  public function givenNonExistingCompanyId_whenFindOneById_shouldReturnNull(): void {
    // given
    $company = $this->company(1);
    $this->companyRepository->save($company);

    $nonExistingCompanyId = $company->getId() + 1;

    // when
    $entity = $this->companyRepository->findOneById($nonExistingCompanyId);

    // then
    Assert::assertNull($entity);
  }

  /**
   * @test
   */
  public function givenValid_whenFindOneById_shouldReturnEntity(): void {
    // given
    $company = $this->company(1);
    $this->companyRepository->save($company);

    // when
    $entity = $this->companyRepository->findOneById($company->getId());

    // then
    Assert::assertNotNull($entity);
    Assert::assertEquals($entity, $company);
  }

  private function company(int $iterator): Company {
    return (new Company())
        ->setName('name#' . $iterator)
        ->setAddress(
            (new Address())
                ->setStreet('street#' . $iterator)
                ->setCity('city#' . $iterator)
                ->setPostcode('00-0' . $iterator));
  }
}