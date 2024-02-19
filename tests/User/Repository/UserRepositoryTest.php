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

  /**
   * @test
   */
  public function givenNonExistingUsername_whenExistsByUsername_shouldReturnFalse(): void {
    // given
    $persistedUser = $this->user('username');
    $this->userRepository->save($persistedUser);

    $nonExistingUsername = $persistedUser->getUsername() . '#nonExisting';

    // when
    $isExists = $this->userRepository->existsByUsername($nonExistingUsername);

    // then
    Assert::assertFalse($isExists);
  }

  /**
   * @test
   */
  public function givenExistingUsername_whenExistsByUsername_shouldReturnTrue(): void {
    // given
    $persistedUser = $this->user('username');
    $this->userRepository->save($persistedUser);

    // when
    $isExists = $this->userRepository->existsByUsername($persistedUser->getUsername());

    // then
    Assert::assertTrue($isExists);
  }

  /**
   * @test
   */
  public function givenNonExistingEmail_whenExistsByUsername_shouldReturnFalse(): void {
    // given
    $persistedUser = $this->user('username');
    $this->userRepository->save($persistedUser);

    $nonExistingEmail = $persistedUser->getEmail() . '#nonExisting';

    // when
    $isExists = $this->userRepository->existsByEmail($nonExistingEmail);

    // then
    Assert::assertFalse($isExists);
  }

  /**
   * @test
   */
  public function givenExistingEmail_whenExistsByUsername_shouldReturnTrue(): void {
    // given
    $persistedUser = $this->user('username');
    $this->userRepository->save($persistedUser);

    // when
    $isExists = $this->userRepository->existsByEmail($persistedUser->getEmail());

    // then
    Assert::assertTrue($isExists);
  }

  private function user(string $username): User {
    return (new User())
        ->setUsername($username)
        ->setPassword('password')
        ->setEmail('email@example.com')
        ->setCompany($this->company);
  }
}