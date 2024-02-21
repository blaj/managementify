<?php

namespace App\Common\Service;

use App\Common\Entity\CompanyContextInterface;
use App\Common\Entity\Dictionary;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;

class DictionaryExistsService {

  public function __construct(private readonly EntityManagerInterface $entityManager) {}

  /**
   * @template T of Dictionary&CompanyContextInterface
   * @param class-string<T> $entityClass
   */
  public function existsByCodeAndCompanyId(
      string $entityClass,
      string $code,
      int $companyId): bool {
    /** @phpstan-ignore-next-line */
    return $this->entityManager
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(entity) > 0 THEN true ELSE false END as isExists 
            FROM '
            . $entityClass
            . ' entity 
            WHERE 
              entity.code = :code 
              AND entity.company = :companyId')
        ->setParameter('code', $code, Types::STRING)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getSingleScalarResult();
  }

  /**
   * @template T of Dictionary&CompanyContextInterface
   * @param class-string<T> $entityClass
   */
  public function existsByNotIdAndCodeAndCompanyId(
      string $entityClass,
      int $id,
      string $code,
      int $companyId): bool {
    /** @phpstan-ignore-next-line */
    return $this->entityManager
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(entity) > 0 THEN true ELSE false END as isExists 
            FROM '
            . $entityClass
            . ' entity 
            WHERE 
              entity.id != :id
              AND entity.code = :code 
              AND entity.company = :companyId')
        ->setParameter('id', $id, Types::INTEGER)
        ->setParameter('code', $code, Types::STRING)
        ->setParameter('companyId', $companyId, Types::INTEGER)
        ->getSingleScalarResult();
  }
}