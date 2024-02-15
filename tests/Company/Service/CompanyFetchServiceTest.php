<?php

namespace App\Tests\Company\Service;

use App\Common\Entity\Address;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Company\Service\CompanyFetchService;
use Doctrine\ORM\EntityNotFoundException;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class CompanyFetchServiceTest extends TestCase {

  private CompanyRepository $companyRepository;

  private CompanyFetchService $companyFetchService;

  public function setUp(): void {
    $this->companyRepository = $this->createMock(CompanyRepository::class);

    $this->companyFetchService = new CompanyFetchService($this->companyRepository);
  }

  /**
   * @test
   */
  public function givenNonExistingCompany_whenFetchCompany_shouldThrowException(): void {
    $this->expectException(EntityNotFoundException::class);
    $this->expectExceptionMessage('Company not found');

    // given
    $id = 123;

    $this->companyRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn(null);

    // when
    $company = $this->companyFetchService->fetchCompany($id);

    // then
  }

  /**
   * @test
   */
  public function givenExistingCompany_whenFetchCompany_shouldReturnCompany(): void {
    // given
    $id = 123;
    $company = (new Company())
        ->setId($id)
        ->setName('name')
        ->setAddress((new Address())->setStreet('street')->setCity('city')->setPostcode('00-00'));

    $this->companyRepository
        ->expects(static::once())
        ->method('findOneById')
        ->with($id)
        ->willReturn($company);

    // when
    $returnedCompany = $this->companyFetchService->fetchCompany($id);

    // then
    Assert::assertNotNull($returnedCompany);
    Assert::assertEquals($returnedCompany, $company);
  }
}