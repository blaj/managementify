<?php

namespace App\Client\Repository;

use App\Client\Entity\PreferredHour;
use App\Common\Dto\DateTimeImmutableRange;
use App\Common\Entity\DayOfWeek;
use App\Common\Repository\AbstractSoftDeleteCompanyContextRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteCompanyContextRepository<PreferredHour>
 */
class PreferredHourRepository extends AbstractSoftDeleteCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, PreferredHour::class);
  }

  /**
   * @return array<PreferredHour>
   */
  public function findAllByClientIdAndCompanyId(int $clientId, int $companyId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              preferredHour 
            FROM 
              App\Client\Entity\PreferredHour preferredHour 
            WHERE 
              preferredHour.deleted = false 
              AND preferredHour.client = :clientId
              AND preferredHour.company = :companyId ')
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }

  public function existsByClientIdAndDayOfWeekAndRangeOverlapAndCompanyId(
      int $clientId,
      DayOfWeek $dayOfWeek,
      DateTimeImmutableRange $range,
      int $companyId): bool {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(preferredHour) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\Client\Entity\PreferredHour preferredHour 
            WHERE 
              preferredHour.deleted = false 
              AND preferredHour.client = :clientId 
              AND preferredHour.dayOfWeek = :dayOfWeek 
              AND preferredHour.fromTime <= :toTime 
              AND preferredHour.toTime >= :fromTime 
              AND preferredHour.company = :companyId')
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('dayOfWeek', $dayOfWeek->value, Types::STRING)
        ->setParameter('fromTime', $range->getFrom(), Types::TIME_IMMUTABLE)
        ->setParameter('toTime', $range->getTo(), Types::TIME_IMMUTABLE)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  public function existsByNotIdAndClientIdAndDayOfWeekAndRangeOverlapAndCompanyId(
      int $id,
      int $clientId,
      DayOfWeek $dayOfWeek,
      DateTimeImmutableRange $range,
      int $companyId): bool {
    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(preferredHour) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\Client\Entity\PreferredHour preferredHour 
            WHERE 
              preferredHour.deleted = false 
              AND preferredHour.id != :id
              AND preferredHour.client = :clientId 
              AND preferredHour.dayOfWeek = :dayOfWeek 
              AND preferredHour.fromTime <= :toTime 
              AND preferredHour.toTime >= :fromTime 
              AND preferredHour.company = :companyId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('clientId', $clientId, Types::INTEGER)
        ->setParameter('dayOfWeek', $dayOfWeek->value, Types::STRING)
        ->setParameter('fromTime', $range->getFrom(), Types::TIME_IMMUTABLE)
        ->setParameter('toTime', $range->getTo(), Types::TIME_IMMUTABLE)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getSingleScalarResult();
  }
}