<?php

namespace App\Common\Repository;

use App\Common\Entity\ClientContextInterface;
use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\SoftDeleteEntity;
use Doctrine\DBAL\Types\Types;

/**
 * @template T of ClientContextInterface&CompanyContextInterface&SoftDeleteEntity
 * @extends AbstractSoftDeleteCompanyContextRepository<T>
 */
abstract class AbstractSoftDeleteClientCompanyContextRepository
    extends AbstractSoftDeleteCompanyContextRepository {


  /**
   * @return array<T>
   */
  public function findAllByClientIdAndCompanyId(int $clientId, int $companyId): array {
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
              AND entity.client = :clientId
              AND entity.company = :companyId ')
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }
}