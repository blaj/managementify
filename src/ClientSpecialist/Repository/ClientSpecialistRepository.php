<?php

namespace App\ClientSpecialist\Repository;

use App\ClientSpecialist\Entity\AssignType;
use App\ClientSpecialist\Entity\ClientSpecialist;
use App\Common\Dto\DateTimeImmutableRange;
use App\Common\Repository\AbstractSoftDeleteCompanyContextRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteCompanyContextRepository<ClientSpecialist>
 */
class ClientSpecialistRepository extends AbstractSoftDeleteCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, ClientSpecialist::class);
  }

  /**
   * @return array<ClientSpecialist>
   */
  public function findAllByClientIdAndCompanyId(int $clientId, int $companyId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              clientSpecialist 
            FROM 
              App\ClientSpecialist\Entity\ClientSpecialist clientSpecialist 
            WHERE 
              clientSpecialist.deleted = false 
              AND clientSpecialist.client = :clientId
              AND clientSpecialist.company = :companyId ')
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }

  public function existsByClientIdAndAssignTypeAndRangeOverlapAndCompanyId(
      int $clientId,
      AssignType $assignType,
      DateTimeImmutableRange $range,
      int $companyId): bool {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(clientSpecialist) > 0 THEN true ELSE false END AS isExists 
            FROM 
              App\ClientSpecialist\Entity\ClientSpecialist clientSpecialist 
            WHERE 
              clientSpecialist.deleted = false 
              AND clientSpecialist.client = :clientId 
              AND (clientSpecialist.fromDate IS NULL OR clientSpecialist.fromDate <= :toDate OR CAST(:toDate AS DATE) IS NULL) 
              AND (clientSpecialist.toDate IS NULL OR clientSpecialist.toDate >= :fromDate OR CAST(:fromDate AS DATE) IS NULL) 
              AND UPPER(clientSpecialist.assignType) = UPPER(:assignType) 
              AND clientSpecialist.company = :companyId')
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('assignType', $assignType->value, Types::STRING)
        ->setParameter('fromDate', $range->getFrom(), Types::DATE_IMMUTABLE)
        ->setParameter('toDate', $range->getTo(), Types::DATE_IMMUTABLE)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function existsByNotIdAndClientIdAndAssignTypeAndRangeOverlapAndCompanyId(
      int $id,
      int $clientId,
      AssignType $assignType,
      DateTimeImmutableRange $range,
      int $companyId): bool {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(clientSpecialist) > 0 THEN true ELSE false END AS isExists 
            FROM 
              App\ClientSpecialist\Entity\ClientSpecialist clientSpecialist 
            WHERE 
              clientSpecialist.deleted = false 
              AND clientSpecialist.id != :id 
              AND clientSpecialist.client = :clientId 
              AND (clientSpecialist.fromDate IS NULL OR clientSpecialist.fromDate <= :toDate OR CAST(:toDate AS DATE) IS NULL) 
              AND (clientSpecialist.toDate IS NULL OR clientSpecialist.toDate >= :fromDate OR CAST(:fromDate AS DATE) IS NULL) 
              AND UPPER(clientSpecialist.assignType) = UPPER(:assignType) 
              AND clientSpecialist.company = :companyId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('assignType', $assignType->value, Types::STRING)
        ->setParameter('fromDate', $range->getFrom(), Types::DATE_IMMUTABLE)
        ->setParameter('toDate', $range->getTo(), Types::DATE_IMMUTABLE)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getSingleScalarResult();
  }
}