<?php

namespace App\Tests\User\Repository;

use App\Common\Entity\Address;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Tests\RepositoryTestCase;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use PHPUnit\Framework\Assert;

class UserRepositoryTest extends RepositoryTestCase {

  private UserRepository $userRepository;
  private CompanyRepository $companyRepository;

  private Company $company;

  public function setUp(): void {
    parent::setUp();

    $this->userRepository = self::getService(UserRepository::class);
    $this->companyRepository = self::getService(CompanyRepository::class);

    $this->company = (new Company())
        ->setName('name')
        ->setAddress((new Address())->setStreet('street')->setCity('city')->setPostcode('00-00'));
    $this->companyRepository->save($this->company);
  }

  /**
   * @test
   */
  public function givenNonExistingUsername_whenFindOneByUsername_shouldReturnNull(): void {
    // given
    $persistedUser = $this->user('username');
    $this->userRepository->save($persistedUser);

    $nonExistingUsername = $persistedUser->getUsername() . '#nonExisting';

    // when
    $user = $this->userRepository->findOneByUsername($nonExistingUsername);

    // then
    Assert::assertNull($user);
  }

  /**
   * @test
   */
  public function givenExistingUsername_whenFindOneByUsername_shouldReturnEntity(): void {
    // given
    $existingUsername = 'username';

    $persistedUser = $this->user($existingUsername);
    $this->userRepository->save($persistedUser);

    // when
    $user = $this->userRepository->findOneByUsername($existingUsername);

    // then
    Assert::assertNotNull($user);
    Assert::assertEquals($user, $persistedUser);
  }

  private function user(string $username): User {
    return (new User())
        ->setUsername($username)
        ->setPassword('password')
        ->setCompany($this->company);
  }
}