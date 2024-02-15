<?php

namespace App\Tests\Security\Service;

use App\Company\Entity\Company;
use App\Security\Dto\UserData;
use App\Security\Service\UserDataProviderService;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\Exception\UserNotFoundException;
use Symfony\Component\Security\Core\User\InMemoryUser;

class UserDataProviderServiceTest extends TestCase {

  private UserRepository $userRepository;

  private UserDataProviderService $userDataProviderService;

  public function setUp(): void {
    $this->userRepository = $this->createMock(UserRepository::class);

    $this->userDataProviderService = new UserDataProviderService($this->userRepository);
  }

  /**
   * @test
   */
  public function givenNonExistingUser_whenLoadUserByIdentifier_shouldThrowException(): void {
    $this->expectException(UserNotFoundException::class);
    $this->expectExceptionMessage('User "nonExisting" not found.');

    // given
    $identifier = 'nonExisting';

    $this->userRepository
        ->expects(static::once())
        ->method('findOneByUsername')
        ->with($identifier)
        ->willReturn(null);

    // when
    $this->userDataProviderService->loadUserByIdentifier($identifier);

    // then
  }

  /**
   * @test
   */
  public function givenExistingUser_whenLoadUserByIdentifier_shouldReturnDto(): void {
    // given
    $identifier = 'username';
    $password = 'password';
    $companyId = 123;

    $user = $this->user($identifier, $password, $companyId);

    $this->userRepository
        ->expects(static::once())
        ->method('findOneByUsername')
        ->with($identifier)
        ->willReturn($user);

    // when
    $dto = $this->userDataProviderService->loadUserByIdentifier($identifier);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->getUserIdentifier(), $identifier);
    Assert::assertEquals($dto->getPassword(), $password);
    Assert::assertEquals($dto->getCompanyId(), $companyId);
    Assert::assertEquals(['ROLE_USER'], $dto->getRoles());
  }

  /**
   * @test
   */
  public function givenNonInstanceUserData_whenRefreshUser_shouldThrowException(): void {
    $this->expectException(UnsupportedUserException::class);
    $this->expectExceptionMessage('User instance is not supported.');

    // given
    $nonInstance = new InMemoryUser('username', 'password');

    // when
    $this->userDataProviderService->refreshUser($nonInstance);

    // then
  }

  /**
   * @test
   */
  public function givenNonExistingUser_whenRefreshUser_shouldThrowException(): void {
    $this->expectException(UserNotFoundException::class);
    $this->expectExceptionMessage('User "nonExisting" not found.');

    // given
    $identifier = 'nonExisting';

    $this->userRepository
        ->expects(static::once())
        ->method('findOneByUsername')
        ->with($identifier)
        ->willReturn(null);

    $userData = (new UserData())->setUserIdentifier($identifier);

    // when
    $this->userDataProviderService->refreshUser($userData);

    // then
  }

  /**
   * @test
   */
  public function givenExistingUser_whenRefreshUser_shouldReturnDto(): void {
    // given
    $identifier = 'username';
    $password = 'password';
    $companyId = 123;

    $user = $this->user($identifier, $password, $companyId);

    $this->userRepository
        ->expects(static::once())
        ->method('findOneByUsername')
        ->with($identifier)
        ->willReturn($user);

    $userData = (new UserData())->setUserIdentifier($identifier);

    // when
    $dto = $this->userDataProviderService->refreshUser($userData);

    // then
    Assert::assertNotNull($dto);
    Assert::assertEquals($dto->getUserIdentifier(), $identifier);
    Assert::assertEquals($dto->getPassword(), $password);
    Assert::assertEquals($dto->getCompanyId(), $companyId);
    Assert::assertEquals(['ROLE_USER'], $dto->getRoles());
  }

  /**
   * @test
   */
  public function givenNonSupportedClass_whenSupportsClass_shouldReturnFalse(): void {
    // given
    $nonSupportedClass = User::class;

    // when
    $isSupports = $this->userDataProviderService->supportsClass($nonSupportedClass);

    // then
    Assert::assertFalse($isSupports);
  }

  /**
   * @test
   */
  public function givenSupportedClass_whenSupportsClass_shouldReturnTrue(): void {
    // given
    $nonSupportedClass = UserData::class;

    // when
    $isSupports = $this->userDataProviderService->supportsClass($nonSupportedClass);

    // then
    Assert::assertTrue($isSupports);
  }

  private function user(string $username, string $password, int $companyId): User {
    return (new User())
        ->setUsername($username)
        ->setPassword($password)
        ->setCompany((new Company())->setId($companyId));
  }
}