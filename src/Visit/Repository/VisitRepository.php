<?php

namespace App\Visit\Repository;

use App\Common\Repository\AbstractSoftDeleteRepository;
use App\Visit\Entity\Visit;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteRepository<Visit>
 */
class VisitRepository extends AbstractSoftDeleteRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Visit::class);
  }

  /**
   * @return array<Visit>
   */
  public function findAllBySpecialistIdAndOnDate(
      int $specialistId,
      DateTimeImmutable $date): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              visit 
            FROM 
              App\Visit\Entity\Visit visit 
            WHERE 
              visit.deleted = false 
              AND visit.specialist = :specialistId 
              AND CAST(visit.fromTime AS DATE) = :date')
        ->setParameter('specialistId', $specialistId, Types::INTEGER)
        ->setParameter('date', $date, Types::DATE_IMMUTABLE)
        ->getResult();
  }
}