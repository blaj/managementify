<?php

namespace App\Common\Repository;

use App\Common\Entity\Dictionary;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;

/**
 * @template T of Dictionary
 * @extends ServiceEntityRepository<T>
 */
abstract class AbstractDictionaryRepository extends ServiceEntityRepository {

  /**
   * @param T $entity
   */
  public function save($entity, bool $flush = true): void {
    $this->getEntityManager()->persist($entity);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function archiveById(int $id): void {
    $this->getEntityManager()
        ->createQuery(
            '
            UPDATE 
              ' . $this->getClassName() . ' entity 
            SET 
              entity.archived = true 
            WHERE 
              entity.id = :id')
        ->setParameter('id', $id, Types::INTEGER)
        ->execute();
  }

  public function unArchiveById(int $id): void {
    $this->getEntityManager()
        ->createQuery(
            '
            UPDATE 
              ' . $this->getClassName() . ' entity 
            SET 
              entity.archived = false 
            WHERE 
              entity.id = :id')
        ->setParameter('id', $id, Types::INTEGER)
        ->execute();
  }

  /**
   * @return array<T>
   */
  public function findAllByCompanyId(int $companyId): array {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
            WHERE 
              entity.company = :companyId ')
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }

  /**
   * @return T|null
   */
  public function findOneByIdAndCompanyId(int $id, int $companyId): ?object {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
            WHERE 
              entity.id = :id
              AND entity.company = :companyId ')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }
}