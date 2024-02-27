<?php

namespace App\User\Service;

use App\User\Entity\Role;
use App\User\Repository\RoleRepository;
use Doctrine\ORM\EntityNotFoundException;

class RoleFetchService {

  public function __construct(private readonly RoleRepository $roleRepository) {}

  public function fetchRole(int $id, int $companyId): Role {
    return $this->roleRepository->findOneByIdAndCompanyId($id, $companyId)
        ??
        throw new EntityNotFoundException('Role not found');
  }
}