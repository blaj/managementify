<?php

namespace App\User\Service;

use App\Company\Service\CompanyFetchService;
use App\User\Dto\RoleCreateRequest;
use App\User\Dto\RoleDetailsDto;
use App\User\Dto\RoleListItemDto;
use App\User\Dto\RoleUpdateRequest;
use App\User\Entity\PermissionType;
use App\User\Entity\Role;
use App\User\Entity\RolePermission;
use App\User\Mapper\RoleDetailsDtoMapper;
use App\User\Mapper\RoleListItemDtoMapper;
use App\User\Mapper\RoleUpdateRequestMapper;
use App\User\Repository\RolePermissionRepository;
use App\User\Repository\RoleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class RoleService {

  public function __construct(
      private readonly RoleRepository $roleRepository,
      private readonly RolePermissionRepository $rolePermissionRepository,
      private readonly CompanyFetchService $companyFetchService,
      private readonly EntityManagerInterface $entityManager) {}

  /**
   * @return array<RoleListItemDto>
   */
  public function getList(int $companyId): array {
    return array_filter(
        array_map(
            fn (?Role $role) => RoleListItemDtoMapper::map($role),
            $this->roleRepository->findAllByCompanyId($companyId)),
        fn (?RoleListItemDto $dto) => $dto !== null);
  }

  public function getDetails(int $id, int $companyId): ?RoleDetailsDto {
    return RoleDetailsDtoMapper::map(
        $this->roleRepository->findOneByIdAndCompanyId($id, $companyId));
  }

  public function create(RoleCreateRequest $roleCreateRequest, int $companyId): void {
    $role = new Role();
    $role->setCompany($this->companyFetchService->fetchCompany($companyId));
    $role->setCode($roleCreateRequest->getCode());
    $role->setName($roleCreateRequest->getName());

    $rolePermissions =
        array_map(
            fn (PermissionType $permissionType) => (new RolePermission())
                ->setRole($role)
                ->setType($permissionType),
            $roleCreateRequest->getPermissionTypes());

    $this->entityManager->beginTransaction();
    $this->roleRepository->save($role, false);
    array_walk(
        $rolePermissions,
        fn (RolePermission $rolePermission) => $this->rolePermissionRepository->save(
            $rolePermission,
            false));
    $this->entityManager->flush();
    $this->entityManager->commit();
  }

  public function getUpdateRequest(int $id, int $companyId): ?RoleUpdateRequest {
    return RoleUpdateRequestMapper::map(
        $this->roleRepository->findOneByIdAndCompanyId($id, $companyId));
  }

  public function update(int $id, RoleUpdateRequest $roleUpdateRequest, int $companyId): void {
    $role = $this->fetchRole($id, $companyId);

    $newRolePermissions =
        $this->getNewRolePermissions($role, $roleUpdateRequest->getPermissionTypes());
    $deletedRolePermissions =
        $this->getDeletedRolePermissions(
            $role->getPermissions()->toArray(),
            $roleUpdateRequest->getPermissionTypes());

    $this->entityManager->beginTransaction();
    array_walk(
        $newRolePermissions,
        fn (RolePermission $rolePermission) => $this->rolePermissionRepository->save(
            $rolePermission,
            false));
    array_walk(
        $deletedRolePermissions,
        fn (RolePermission $rolePermission) => $this->rolePermissionRepository->softDeleteById(
            $rolePermission->getId()));
    $this->entityManager->flush();
    $this->entityManager->commit();
  }

  public function archive(int $id, int $companyId): void {
    $this->roleRepository->archiveById($this->fetchRole($id, $companyId)->getId());
  }

  public function unArchive(int $id, int $companyId): void {
    $this->roleRepository->unArchiveById($this->fetchRole($id, $companyId)->getId());
  }

  private function fetchRole(int $id, int $companyId): Role {
    return $this->roleRepository->findOneByIdAndCompanyId($id, $companyId)
        ??
        throw new EntityNotFoundException('Role not found');
  }

  /**
   * @param array<PermissionType> $permissionTypes
   *
   * @return array<RolePermission>
   */
  private function getNewRolePermissions(Role $role, array $permissionTypes): array {
    $newRolePermissions = [];

    foreach ($permissionTypes as $permissionType) {
      $isNew = true;

      foreach ($role->getPermissions() as $actualRolePermission) {
        if ($actualRolePermission->getType() === $permissionType) {
          $isNew = false;
        }
      }

      if ($isNew) {
        $newRolePermissions[] =
            (new RolePermission())
                ->setType($permissionType)
                ->setRole($role);
      }
    }

    return $newRolePermissions;
  }

  /**
   * @param array<RolePermission> $actualRolePermissions
   * @param array<PermissionType> $permissionTypes
   *
   * @return array<RolePermission>
   */
  private function getDeletedRolePermissions(
      array $actualRolePermissions,
      array $permissionTypes): array {
    $deletedRolePermissions = [];

    foreach ($actualRolePermissions as $actualRolePermission) {
      $isDeleted = true;

      foreach ($permissionTypes as $permissionType) {
        if ($actualRolePermission->getType() === $permissionType) {
          $isDeleted = false;
        }
      }

      if ($isDeleted) {
        $deletedRolePermissions[] = $actualRolePermission;
      }
    }

    return $deletedRolePermissions;
  }
}