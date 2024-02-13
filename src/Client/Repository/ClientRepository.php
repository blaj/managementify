<?php

namespace App\Client\Repository;

use App\Client\Dto\ClientPaginatedListCriteria;
use App\Client\Dto\ClientPaginatedListFilter;
use App\Client\Entity\Client;
use App\Common\Dto\EntityPage;
use App\Common\Dto\QueryWithCriteriaWrapper;
use App\Common\Mapper\QueryPaginationMapper;
use App\Common\Mapper\QueryWithCriteriaWrapperStatementMapper;
use App\Common\PaginatedList\Mapper\SortMapper;
use App\Common\Repository\AbstractSoftDeleteRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteRepository<Client>
 */
class ClientRepository extends AbstractSoftDeleteRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Client::class);
  }

  /**
   * @return EntityPage<Client>
   */
  public function findAllByCriteria(ClientPaginatedListCriteria $criteria): EntityPage {
    $statement = 'SELECT client FROM App\Client\Entity\Client client WHERE client.deleted = false ';

    $parameters = [];

    $queryWithCriteriaWrapper = self::getQueryWithCriteriaWrapper($criteria->getFilter());

    $statement .= QueryWithCriteriaWrapperStatementMapper::map($queryWithCriteriaWrapper);
    $parameters = array_merge($parameters, $queryWithCriteriaWrapper->getParameters());

    $statement .= SortMapper::map($criteria->getSort(), 'client.id');

    $query = $this->getEntityManager()
        ->createQuery($statement)
        ->setParameters($parameters);

    $result = QueryPaginationMapper::map($query, $criteria->getPageCriteria())->getResult();

    return (new EntityPage(Client::class))
        /** @phpstan-ignore-next-line */
        ->setItems($result)
        ->setTotalItems($this->countAllByCriteria($criteria));
  }

  private function countAllByCriteria(ClientPaginatedListCriteria $criteria): int {
    $statement = 'SELECT COUNT(client) FROM App\Client\Entity\Client client WHERE client.deleted = false';

    $parameters = [];

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
      ?ClientPaginatedListFilter $clientPaginatedListFilter): QueryWithCriteriaWrapper {
    if ($clientPaginatedListFilter === null) {
      return QueryWithCriteriaWrapper::empty();
    }

    $queryWithCriteriaWrapper = new QueryWithCriteriaWrapper();

    if ($clientPaginatedListFilter->getSearch() !== null) {
      $queryWithCriteriaWrapper->addStatement(
          ' (UPPER(client.firstname) LIKE UPPER(:search) OR UPPER(client.surname) LIKE UPPER(:search) OR UPPER(client.foreignId) LIKE UPPER(:search)) ');
      $queryWithCriteriaWrapper->addParameter(
          '%' . $clientPaginatedListFilter->getSearch() . '%',
          'search');
    }

    return $queryWithCriteriaWrapper;
  }
}