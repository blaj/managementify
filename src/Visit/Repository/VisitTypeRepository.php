<?php

namespace App\Visit\Repository;

use App\Visit\Entity\VisitType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VisitType>
 */
class VisitTypeRepository extends ServiceEntityRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, VisitType::class);
  }

  public function save(VisitType $visitType, bool $flush = true): void {
    $this->getEntityManager()->persist($visitType);

    if ($flush) {
      $this->getEntityManager()->flush();
    }
  }

  public function archiveById(int $id): void {
    $this->getEntityManager()
        ->createQuery(
            '
            UPDATE 
              App\Visit\Entity\VisitType visitType 
            SET 
              visitType.archived = true 
            WHERE 
              visitType.id = :id')
        ->setParameter('id', $id, Types::INTEGER)
        ->execute();
  }

  public function unArchiveById(int $id): void {
    $this->getEntityManager()
        ->createQuery(
            '
            UPDATE 
              App\Visit\Entity\VisitType visitType 
            SET 
              visitType.archived = false 
            WHERE 
              visitType.id = :id')
        ->setParameter('id', $id, Types::INTEGER)
        ->execute();
  }

  /**
   * @return array<VisitType>
   */
  public function findAllByCompanyId(int $companyId): array {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              visitType 
            FROM 
              App\Visit\Entity\VisitType visitType 
            WHERE 
              visitType.company = :companyId ')
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getResult();
  }

  public function findOneByIdAndCompanyId(int $id, int $companyId): ?VisitType {
    return $this->getEntityManager()
        ->createQuery(
            '
            SELECT 
              visitType 
            FROM 
              App\Visit\Entity\VisitType visitType 
            WHERE 
              visitType.id = :id
              AND visitType.company = :companyId ')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }
}