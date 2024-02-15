<?php

namespace App\Company\Repository;

use App\Common\Repository\AbstractSoftDeleteRepository;
use App\Company\Entity\Company;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractSoftDeleteRepository<Company>
 */
class CompanyRepository extends AbstractSoftDeleteRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, Company::class);
  }
}