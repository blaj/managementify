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

  public function existsByUsername(string $username): bool {
    return $this
        ->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(user) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.username = :username 
              AND user.deleted = false')
        ->setParameter('username', $username, Types::STRING)
        ->getSingleScalarResult() > 0;
  }

  public function existsByEmail(string $email): bool {
    return $this
            ->getEntityManager()
            ->createQuery(
                '
            SELECT 
              CASE WHEN COUNT(user) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.email = :email 
              AND user.deleted = false')
            ->setParameter('email', $email, Types::STRING)
            ->getSingleScalarResult() > 0;
  }
}