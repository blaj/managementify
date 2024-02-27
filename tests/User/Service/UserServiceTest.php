<?php

namespace App\Tests\User\Service;

use App\Client\Service\ClientFetchService;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Company\Service\CompanyFetchService;
use App\Specialist\Service\SpecialistFetchService;
use App\User\Dto\UserRegisterRequest;
use App\User\Entity\PermissionType;
use App\User\Entity\Role;
use App\User\Entity\User;
use App\User\Repository\RolePermissionRepository;
use App\User\Repository\RoleRepository;
use App\User\Repository\UserRepository;
use App\User\Service\RoleFetchService;
use App\User\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserServiceTest extends TestCase {

  private UserRepository $userRepository;
  private RoleRepository $roleRepository;
  private RolePermissionRepository $rolePermissionRepository;
  private CompanyRepository $companyRepository;
  private CompanyFetchService $companyFetchService;
  private RoleFetchService $roleFetchService;
  private SpecialistFetchService $specialistFetchService;
  private ClientFetchService $clientFetchService;
  private EntityManagerInterface $entityManager;
  private UserPasswordHasherInterface $userPasswordHasher;

  private UserService $userService;

  public function setUp(): void {
    $this->userRepository = $this->createMock(UserRepository::class);
    $this->roleRepository = $this->createMock(RoleRepository::class);
    $this->rolePermissionRepository = $this->createMock(RolePermissionRepository::class);
    $this->companyRepository = $this->createMock(CompanyRepository::class);
    $this->companyFetchService = $this->createMock(CompanyFetchService::class);
    $this->roleFetchService = $this->createMock(RoleFetchService::class);
    $this->specialistFetchService = $this->createMock(SpecialistFetchService::class);
    $this->clientFetchService = $this->createMock(ClientFetchService::class);
    $this->entityManager = $this->createMock(EntityManagerInterface::class);
    $this->userPasswordHasher = $this->createMock(UserPasswordHasherInterface::class);

    $this->userService =
        new UserService(
            $this->userRepository,
            $this->roleRepository,
            $this->rolePermissionRepository,
            $this->companyRepository,
            $this->companyFetchService,
            $this->roleFetchService,
            $this->specialistFetchService,
            $this->clientFetchService,
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

    $this->roleRepository
        ->expects(static::once())
        ->method('save')
        ->with(
            static::callback(
                fn (Role $role) => $role->getCode() === 'ADMIN' && $role->getName() === 'Admin'));

    $this->rolePermissionRepository
        ->expects(static::exactly(count(PermissionType::cases())))
        ->method('save');

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