<?php

namespace App\Common\Repository;

use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\SoftDeleteEntity;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;

/**
 * @template T of SoftDeleteEntity & CompanyContextInterface
 * @extends AbstractSoftDeleteRepository<T>
 */
abstract class AbstractSoftDeleteCompanyContextRepository extends AbstractSoftDeleteRepository {

  /**
   * @return T|null
   * @throws NonUniqueResultException
   */
  public function findOneByIdAndCompany(int $id, int $companyId): ?object {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              entity 
            FROM 
              ' . $this->getClassName() . ' entity 
            WHERE 
              entity.deleted = false 
              AND entity.id = :id 
              AND entity.company = :companyId ')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
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
              entity.deleted = false 
              AND entity.company = :companyId ')
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }
}