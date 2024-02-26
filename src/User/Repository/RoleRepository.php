<?php

namespace App\User\Repository;

use App\Common\Repository\AbstractDictionaryRepository;
use App\User\Entity\Role;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractDictionaryRepository<Role>
 */
class RoleRepository extends AbstractDictionaryRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Role::class);
  }
}