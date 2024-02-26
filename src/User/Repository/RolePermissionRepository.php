<?php

namespace App\User\Repository;

use App\Common\Repository\AbstractSoftDeleteRepository;
use App\User\Entity\RolePermission;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteRepository<RolePermission>
 */
class RolePermissionRepository extends AbstractSoftDeleteRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, RolePermission::class);
  }
}