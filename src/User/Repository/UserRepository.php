<?php

namespace App\User\Repository;

use App\Common\Repository\AbstractSoftDeleteCompanyContextRepository;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteCompanyContextRepository<User>
 */
class UserRepository extends AbstractSoftDeleteCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, User::class);
  }

  /**
   * @throws NonUniqueResultException
   */
  public function findOneByUsername(string $username): ?User {
    return $this
        ->getEntityManager()
        ->createQuery(
            '
            SELECT 
              user 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.username = :username 
              AND user.deleted = false')
        ->setParameter('username', $username, Types::STRING)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }
}