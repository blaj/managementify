<?php

namespace App\Visit\Repository;

use App\Common\Repository\AbstractDictionaryRepository;
use App\Visit\Entity\VisitType;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends AbstractDictionaryRepository<VisitType>
 */
class VisitTypeRepository extends AbstractDictionaryRepository {

  public function __construct(ManagerRegistry $registry) {
    parent::__construct($registry, VisitType::class);
  }
}