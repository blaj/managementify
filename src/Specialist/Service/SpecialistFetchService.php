<?php

namespace App\Specialist\Service;

use App\Specialist\Entity\Specialist;
use App\Specialist\Repository\SpecialistRepository;
use Doctrine\ORM\EntityNotFoundException;

class SpecialistFetchService {

  public function __construct(private readonly SpecialistRepository $specialistRepository) {}

  public function fetchSpecialist(int $id, int $companyId): Specialist {
    return $this->specialistRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('Specialist not found');
  }
}