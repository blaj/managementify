<?php

namespace App\Visit\Repository;

use App\Common\Repository\AbstractSoftDeleteCompanyContextRepository;
use App\Visit\Entity\Visit;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteCompanyContextRepository<Visit>
 */
class VisitRepository extends AbstractSoftDeleteCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Visit::class);
  }

  /**
   * @return array<Visit>
   */
  public function findAllBySpecialistIdAndOnDateAndCompanyId(
      int $specialistId,
      DateTimeImmutable $date, int $companyId): array {
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
              AND CAST(visit.fromTime AS DATE) = :date 
              AND visit.company = :companyId ')
        ->setParameter('specialistId', $specialistId, Types::INTEGER)
        ->setParameter('date', $date, Types::DATE_IMMUTABLE)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }
}