<?php

namespace App\Tests\User\Service;

use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\User\Dto\UserRegisterRequest;
use App\User\Entity\User;
use App\User\Repository\UserRepository;
use App\User\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase {

  private UserRepository $userRepository;
  private CompanyRepository $companyRepository;
  private EntityManagerInterface $entityManager;
  private UserPasswordHasherInterface $userPasswordHasher;

  private UserService $userService;

  public function setUp(): void {
    $this->userRepository = $this->createMock(UserRepository::class);
    $this->companyRepository = $this->createMock(CompanyRepository::class);
    $this->entityManager = $this->createMock(EntityManagerInterface::class);
    $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

    $this->userService =
        new UserService(
            $this->userRepository,
            $this->companyRepository,
            $this->entityManager,
            $this->userPasswordHasher);
  }

  /**
   * @test
   */
  public function givenValid_whenRegister_shouldSave(): void {
    // given
    $userRegisterRequest = (new UserRegisterRequest())
        ->setUsername('username')
        ->setPassword('password')
        ->setEmail('email@example.com')
        ->setCompanyName('Company')
        ->setCompanyCity('City')
        ->setCompanyStreet('Street')
        ->setCompanyPostcode('00-000');

    $hashedPassword = 'hashedPassword';

    $this->userPasswordHasher
        ->expects(static::once())
        ->method('hashPassword')
        ->with(static::anything(), static::equalTo($userRegisterRequest->getPassword()))
        ->willReturn($hashedPassword);

    $this->entityManager
        ->expects(static::once())
        ->method('beginTransaction');

    $this->companyRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            static::callback(
                fn (Company $company) => $company->getName()
                    === $userRegisterRequest->getCompanyName()
                    && $company->getAddress()->getCity() === $userRegisterRequest->getCompanyCity()
                    && $company->getAddress()->getStreet()
                    === $userRegisterRequest->getCompanyStreet()
                    && $company->getAddress()->getPostcode()
                    === $userRegisterRequest->getCompanyPostcode()));

    $this->userRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            static::callback(
                fn (User $user) => $user->getUsername() === $userRegisterRequest->getUsername()
                    && $user->getEmail() === $userRegisterRequest->getEmail()
                    && $user->getPassword() === $hashedPassword));

    $this->entityManager
        ->expects(static::once())
        ->method('flush');

    $this->entityManager
        ->expects(static::once())
        ->method('commit');

    // when
    $this->userService->register($userRegisterRequest);

    // then
  }
}