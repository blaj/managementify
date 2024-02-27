<?php

namespace App\User\Repository;

use App\Common\Dto\EntityPage;
use App\Common\Dto\QueryWithCriteriaWrapper;
use App\Common\Mapper\QueryPaginationMapper;
use App\Common\Mapper\QueryWithCriteriaWrapperStatementMapper;
use App\Common\PaginatedList\Mapper\SortMapper;
use App\Common\Repository\AbstractSoftDeleteCompanyContextRepository;
use App\User\Dto\UserPaginatedListCriteria;
use App\User\Dto\UserPaginatedListFilter;
use App\User\Entity\User;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteCompanyContextRepository<User>
 */
class UserRepository extends AbstractSoftDeleteCompanyContextRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, User::class);
  }

  /**
   * @throws NonUniqueResultException
   */
  public function findOneByUsername(string $username): ?User {
    return $this
        ->getEntityManager()
        ->createQuery(
            '
            SELECT 
              user 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.username = :username 
              AND user.deleted = false')
        ->setParameter('username', $username, Types::STRING)
        ->getOneOrNullResult(AbstractQuery::HYDRATE_OBJECT);
  }

  public function existsByUsername(string $username): bool {
    return $this
        ->getEntityManager()
        ->createQuery(
            '
            SELECT 
              CASE WHEN COUNT(user) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.username = :username 
              AND user.deleted = false')
        ->setParameter('username', $username, Types::STRING)
        ->getSingleScalarResult() > 0;
  }

  public function existsByNotIdAndUsername(int $id, string $username): bool {
    return $this
            ->getEntityManager()
            ->createQuery(
                '
            SELECT 
              CASE WHEN COUNT(user) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.id != :id 
              AND user.username = :username 
              AND user.deleted = false')
            ->setParameter('id', $id, Types::INTEGER)
            ->setParameter('username', $username, Types::STRING)
            ->getSingleScalarResult() > 0;
  }

  public function existsByEmail(string $email): bool {
    return $this
            ->getEntityManager()
            ->createQuery(
                '
            SELECT 
              CASE WHEN COUNT(user) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.email = :email 
              AND user.deleted = false')
            ->setParameter('email', $email, Types::STRING)
            ->getSingleScalarResult() > 0;
  }

  public function existsByNotIdAndEmail(int $id, string $email): bool {
    return $this
            ->getEntityManager()
            ->createQuery(
                '
            SELECT 
              CASE WHEN COUNT(user) > 0 THEN true ELSE false END as isExists 
            FROM 
              App\User\Entity\User user 
            WHERE 
              user.id != :id 
              AND user.email = :email 
              AND user.deleted = false')
            ->setParameter('id', $id, Types::INTEGER)
            ->setParameter('email', $email, Types::STRING)
            ->getSingleScalarResult() > 0;
  }

  /**
   * @return EntityPage<User>
   */
  public function findAllByCriteria(
      UserPaginatedListCriteria $criteria,
      int $companyId): EntityPage {
    $statement =
        ' SELECT user FROM App\User\Entity\User user WHERE user.deleted = false AND user.company = :companyId ';

    $parameters = [
        'companyId' => $companyId
    ];

    $queryWithCriteriaWrapper = self::getQueryWithCriteriaWrapper($criteria->getFilter());

    $statement .= QueryWithCriteriaWrapperStatementMapper::map($queryWithCriteriaWrapper);
    $parameters = array_merge($parameters, $queryWithCriteriaWrapper->getParameters());

    $statement .= SortMapper::map($criteria->getSort(), 'user.id');

    $query = $this->getEntityManager()
        ->createQuery($statement)
        ->setParameters($parameters);

    $result = QueryPaginationMapper::map($query, $criteria->getPageCriteria())->getResult();

    return (new EntityPage(User::class))
        /** @phpstan-ignore-next-line */
        ->setItems($result)
        ->setTotalItems($this->countAllByCriteria($criteria, $companyId));
  }

  private function countAllByCriteria(
      UserPaginatedListCriteria $criteria,
      int $companyId): int {
    $statement =
        ' SELECT COUNT(user) FROM App\User\Entity\User user WHERE user.deleted = false AND user.company = :companyId';

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
      ?UserPaginatedListFilter $userPaginatedListFilter): QueryWithCriteriaWrapper {
    if ($userPaginatedListFilter === null) {
      return QueryWithCriteriaWrapper::empty();
    }

    $queryWithCriteriaWrapper = new QueryWithCriteriaWrapper();

    if ($userPaginatedListFilter->getSearch() !== null) {
      $queryWithCriteriaWrapper->addStatement(
          ' (UPPER(user.username) LIKE UPPER(:search) OR UPPER(user.email) LIKE UPPER(:search)) ');
      $queryWithCriteriaWrapper->addParameter(
          '%' . $userPaginatedListFilter->getSearch() . '%',
          'search');
    }

    return $queryWithCriteriaWrapper;
  }
}