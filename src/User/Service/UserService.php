<?php

namespace App\User\Service;

use App\Common\Entity\Address;
use App\Company\Entity\Company;
use App\Company\Repository\CompanyRepository;
use App\User\Dto\UserRegisterRequest;
use App\User\Entity\PermissionType;
use App\User\Entity\Role;
use App\User\Entity\RolePermission;
use App\User\Entity\User;
use App\User\Repository\RolePermissionRepository;
use App\User\Repository\RoleRepository;
use App\User\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService {

  public function __construct(
      private readonly UserRepository $userRepository,
      private readonly RoleRepository $roleRepository,
      private readonly RolePermissionRepository $rolePermissionRepository,
      private readonly CompanyRepository $companyRepository,
      private readonly EntityManagerInterface $entityManager,
      private readonly UserPasswordHasherInterface $userPasswordHasher) {}

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
}