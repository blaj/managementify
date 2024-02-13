<?php

namespace App\Common\Repository;

use App\Common\Entity\SoftDeleteEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @template T of SoftDeleteEntity
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractSoftDeleteRepository extends ServiceEntityRepository {

  /**
   * @param T $entity
   */
  public function save($entity, bool $flush = true): void {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  /**
   * @return T|null
   * @throws NonUniqueResultException
   */
  public function findOneById(int $id): ?object {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() .' entity 
            WHERE 
              entity.deleted = false 
              AND entity.id = :id ')
        ->setParameter('id', $id, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  public function softDeleteById(int $id): void {
    $this->getEntityManager()
      ->createQuery(
          '
          UPDATE 
            ' . $this->getClassName() . ' entity
          SET 
            entity.deleted = true 
          WHERE 
            entity.id = :id ')
      ->setParameter('id', $id, Types::INTEGER)
      ->execute();
  }
}