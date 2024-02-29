<?php

namespace App\Visit\Service;

use App\Visit\Entity\VisitType;
use App\Visit\Repository\VisitTypeRepository;
use Doctrine\ORM\EntityNotFoundException;

class VisitTypeFetchService {

  public function __construct(private readonly VisitTypeRepository $visitTypeRepository) {}

  public function fetchVisitType(int $id, int $companyId): VisitType {
    return $this->visitTypeRepository->findOneByIdAndCompanyId($id, $companyId)
        ??
        throw new EntityNotFoundException('Visit type not found');
  }
}