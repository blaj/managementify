<?php

namespace App\Specialist\Repository;

use App\Common\Dto\EntityPage;
use App\Common\Dto\QueryWithCriteriaWrapper;
use App\Common\Mapper\QueryPaginationMapper;
use App\Common\Mapper\QueryWithCriteriaWrapperStatementMapper;
use App\Common\PaginatedList\Mapper\SortMapper;
use App\Common\Repository\AbstractSoftDeleteCompanyContextRepository;
use App\Specialist\Dto\SpecialistPaginatedListCriteria;
use App\Specialist\Dto\SpecialistPaginatedListFilter;
use App\Specialist\Entity\Specialist;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteCompanyContextRepository<Specialist>
 */
class SpecialistRepository extends AbstractSoftDeleteCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Specialist::class);
  }

  /**
   * @return EntityPage<Specialist>
   */
  public function findAllByCriteria(
      SpecialistPaginatedListCriteria $criteria,
      int $companyId): EntityPage {
    $statement =
        ' SELECT specialist FROM App\Specialist\Entity\Specialist specialist WHERE specialist.deleted = false AND specialist.company = :companyId ';

    $parameters = [
        'companyId' => $companyId
    ];

    $queryWithCriteriaWrapper = self::getQueryWithCriteriaWrapper($criteria->getFilter());

    $statement .= QueryWithCriteriaWrapperStatementMapper::map($queryWithCriteriaWrapper);
    $parameters = array_merge($parameters, $queryWithCriteriaWrapper->getParameters());

    $statement .= SortMapper::map($criteria->getSort(), 'specialist.id');

    $query = $this->getEntityManager()
        ->createQuery($statement)
        ->setParameters($parameters);

    $result = QueryPaginationMapper::map($query, $criteria->getPageCriteria())->getResult();

    return (new EntityPage(Specialist::class))
        /** @phpstan-ignore-next-line */
        ->setItems($result)
        ->setTotalItems($this->countAllByCriteria($criteria, $companyId));
  }

  private function countAllByCriteria(
      SpecialistPaginatedListCriteria $criteria,
      int $companyId): int {
    $statement =
        ' SELECT COUNT(specialist) FROM App\Specialist\Entity\Specialist specialist WHERE specialist.deleted = false AND specialist.company = :companyId';

    $parameters = [
        'companyId' => $companyId
    ];

    $queryWithCriteriaWrapper = self::getQueryWithCriteriaWrapper($criteria->getFilter());

    $statement .= QueryWithCriteriaWrapperStatementMapper::map($queryWithCriteriaWrapper);
    $parameters = array_merge($parameters, $queryWithCriteriaWrapper->getParameters());

    /** @phpstan-ignore-next-line */
    return $this->getEntityManager()
        ->createQuery($statement)
        ->setParameters($parameters)
        ->getSingleScalarResult();
  }

  private static function getQueryWithCriteriaWrapper(
      ?SpecialistPaginatedListFilter $specialistPaginatedListFilter): QueryWithCriteriaWrapper {
    if ($specialistPaginatedListFilter === null) {
      return QueryWithCriteriaWrapper::empty();
    }

    $queryWithCriteriaWrapper = new QueryWithCriteriaWrapper();

    if ($specialistPaginatedListFilter->getSearch() !== null) {
      $queryWithCriteriaWrapper->addStatement(
          ' (UPPER(specialist.firstname) LIKE UPPER(:search) OR UPPER(specialist.surname) LIKE UPPER(:search) OR UPPER(specialist.foreignId) LIKE UPPER(:search)) ');
      $queryWithCriteriaWrapper->addParameter(
          '%' . $specialistPaginatedListFilter->getSearch() . '%',
          'search');
    }

    return $queryWithCriteriaWrapper;
  }
}