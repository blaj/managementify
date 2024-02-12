<?php

namespace App\Common\Repository;

use App\Common\Entity\SoftDeleteEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

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
}