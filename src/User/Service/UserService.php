<?php

namespace App\User\Service;

use App\Client\Service\ClientFetchService;
use App\Common\Entity\Address;
use App\Common\PaginatedList\Dto\CriteriaWithEntityPageWrapper;
use App\Common\PaginatedList\Dto\PaginatedList;
use App\Common\PaginatedList\Mapper\PageMapper;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\Company\Service\CompanyFetchService;
use App\Specialist\Service\SpecialistFetchService;
use App\User\Dto\UserCreateRequest;
use App\User\Dto\UserDetailsDto;
use App\User\Dto\UserListItemDto;
use App\User\Dto\UserPaginatedListCriteria;
use App\User\Dto\UserPaginatedListFilter;
use App\User\Dto\UserRegisterRequest;
use App\User\Dto\UserUpdateRequest;
use App\User\Entity\PermissionType;
use App\User\Entity\Role;
use App\User\Entity\RolePermission;
use App\User\Entity\User;
use App\User\Mapper\UserDetailsDtoMapper;
use App\User\Mapper\UserListItemDtoMapper;
use App\User\Mapper\UserUpdateRequestMapper;
use App\User\Repository\RolePermissionRepository;
use App\User\Repository\RoleRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {

  public function __construct(
      private readonly UserRepository $userRepository,
      private readonly RoleRepository $roleRepository,
      private readonly RolePermissionRepository $rolePermissionRepository,
      private readonly CompanyRepository $companyRepository,
      private readonly CompanyFetchService $companyFetchService,
      private readonly RoleFetchService $roleFetchService,
      private readonly SpecialistFetchService $specialistFetchService,
      private readonly ClientFetchService $clientFetchService,
      private readonly EntityManagerInterface $entityManager,
      private readonly UserPasswordHasherInterface $userPasswordHasher) {}

  /**
   * @return PaginatedList<UserListItemDto>
   */
  public function getPaginatedListByCriteria(
      UserPaginatedListCriteria $userPaginatedListCriteria,
      int $companyId): PaginatedList {
    $usersPage = $this->userRepository->findAllByCriteria($userPaginatedListCriteria, $companyId);

    return (new PaginatedList(UserListItemDto::class))
        ->setItems(
            array_filter(
                array_map(
                    fn (?User $user) => UserListItemDtoMapper::map($user),
                    $usersPage->getItems()),
                fn (?UserListItemDto $dto) => $dto !== null))
        ->setPage(
            PageMapper::map(
                User::class,
                UserPaginatedListFilter::class,
                (new CriteriaWithEntityPageWrapper(
                    User::class,
                    UserPaginatedListFilter::class))
                    ->setEntityPage($usersPage)
                    ->setCriteria($userPaginatedListCriteria)))
        ->setSort($userPaginatedListCriteria->getSort());
  }

  public function getDetails(int $id, int $companyId): ?UserDetailsDto {
    return UserDetailsDtoMapper::map($this->userRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function create(UserCreateRequest $userCreateRequest, int $companyId): void {
    $user = (new User())
        ->setUsername($userCreateRequest->getUsername())
        ->setEmail($userCreateRequest->getEmail())
        ->setCompany($this->companyFetchService->fetchCompany($companyId));

    if ($userCreateRequest->getRoleId() !== null) {
      $user->setRole(
          $this->roleFetchService->fetchRole($userCreateRequest->getRoleId(), $companyId));
    }

    if ($userCreateRequest->getSpecialistId() !== null) {
      $user->setSpecialist(
          $this->specialistFetchService->fetchSpecialist(
              $userCreateRequest->getSpecialistId(),
              $companyId));
    }

    if ($userCreateRequest->getClientId() !== null) {
      $user->setClient(
          $this->clientFetchService->fetchClient($userCreateRequest->getClientId(), $companyId));
    }

    $user->setPassword(
        $this->userPasswordHasher->hashPassword($user, $userCreateRequest->getPassword()));

    $this->userRepository->save($user);
  }

  public function getUpdateRequest(int $id, int $companyId): ?UserUpdateRequest {
    return UserUpdateRequestMapper::map(
        $this->userRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function update(int $id, UserUpdateRequest $userUpdateRequest, int $companyId): void {
    $user =
        $this->fetchUser($id, $companyId)
            ->setUsername($userUpdateRequest->getUsername())
            ->setEmail($userUpdateRequest->getEmail());

    if ($userUpdateRequest->getPassword() !== null) {
      $user->setPassword(
          $this->userPasswordHasher->hashPassword($user, $userUpdateRequest->getPassword()));
    }

    if ($userUpdateRequest->getRoleId() !== null) {
      $user->setRole(
          $this->roleFetchService->fetchRole($userUpdateRequest->getRoleId(), $companyId));
    }

    if ($userUpdateRequest->getSpecialistId() !== null) {
      $user->setSpecialist(
          $this->specialistFetchService->fetchSpecialist(
              $userUpdateRequest->getSpecialistId(),
              $companyId));
    }

    if ($userUpdateRequest->getClientId() !== null) {
      $user->setClient(
          $this->clientFetchService->fetchClient($userUpdateRequest->getClientId(), $companyId));
    }

    $this->userRepository->save($user);
  }

  public function delete(int $id, int $companyId): void {
    $this->userRepository->softDeleteById($this->fetchUser($id, $companyId)->getId());
  }

  public function register(UserRegisterRequest $userRegisterRequest): void {
    $company = (new Company())
        ->setName($userRegisterRequest->getCompanyName())
        ->setAddress(
            (new Address())
                ->setCity($userRegisterRequest->getCompanyCity())
                ->setStreet($userRegisterRequest->getCompanyStreet())
                ->setPostcode($userRegisterRequest->getCompanyPostcode()));

    $role = new Role();
    $role->setCode('ADMIN');
    $role->setName('Admin');
    $role->setCompany($company);

    $rolePermissions =
        array_map(
            fn (PermissionType $permissionType) => (new RolePermission())
                ->setRole($role)
                ->setType($permissionType),
            PermissionType::cases());

    $user = (new User())
        ->setUsername($userRegisterRequest->getUsername())
        ->setEmail($userRegisterRequest->getEmail())
        ->setCompany($company)
        ->setRole($role);

    $user->setPassword(
        $this->userPasswordHasher->hashPassword($user, $userRegisterRequest->getPassword()));

    $this->entityManager->beginTransaction();
    $this->companyRepository->save($company, false);
    $this->roleRepository->save($role, false);
    array_walk(
        $rolePermissions,
        fn (RolePermission $rolePermission) => $this->rolePermissionRepository->save(
            $rolePermission,
            false));
    $this->userRepository->save($user, false);
    $this->entityManager->flush();
    $this->entityManager->commit();
  }

  private function fetchUser(int $id, int $companyId): User {
    return $this->userRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('User not found');
  }
}