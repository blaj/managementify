<?php

namespace App\Visit\Service;

use App\Client\Service\ClientFetchService;
use App\Common\Utils\DateTimeImmutableUtils;
use App\Company\Service\CompanyFetchService;
use App\Specialist\Service\SpecialistFetchService;
use App\Visit\Dto\VisitCellDataRequest;
use App\Visit\Dto\VisitCreateRequest;
use App\Visit\Entity\Visit;
use App\Visit\Repository\VisitRepository;
use DateTimeImmutable;

class VisitService {

  public function __construct(
      private readonly VisitRepository $visitRepository,
      private readonly ClientFetchService $clientFetchService,
      private readonly SpecialistFetchService $specialistFetchService,
      private readonly CompanyFetchService $companyFetchService,
      private readonly VisitTypeFetchService $visitTypeFetchService) {}

  public function getCreateRequest(VisitCellDataRequest $visitCellDataRequest): VisitCreateRequest {
    return (new VisitCreateRequest())
        ->setDate($visitCellDataRequest->getDate())
        ->setSpecialistId($visitCellDataRequest->getSpecialistId())
        ->setFromTime(new DateTimeImmutable())
        ->setToTime(new DateTimeImmutable());
  }

  public function create(VisitCreateRequest $visitCreateRequest, int $companyId): void {
    $fromTime =
        DateTimeImmutableUtils::appendTimeToDate(
            $visitCreateRequest->getDate(),
            $visitCreateRequest->getFromTime());
    $toTime =
        DateTimeImmutableUtils::appendTimeToDate(
            $visitCreateRequest->getDate(),
            $visitCreateRequest->getToTime());

    if ($fromTime > $toTime) {
      $toTime = DateTimeImmutableUtils::addDay($toTime);
    }

    $visit = (new Visit())
        ->setFromTime($fromTime)
        ->setToTime($toTime)
        ->setNote($visitCreateRequest->getNote())
        ->setClient(
            $this->clientFetchService->fetchClient($visitCreateRequest->getClientId(), $companyId))
        ->setSpecialist(
            $this->specialistFetchService->fetchSpecialist(
                $visitCreateRequest->getSpecialistId(),
                $companyId))
        ->setCompany($this->companyFetchService->fetchCompany($companyId));

    if ($visitCreateRequest->getVisitTypeId() !== null) {
      $visit->setVisitType(
          $this->visitTypeFetchService->fetchVisitType(
              $visitCreateRequest->getVisitTypeId(),
              $companyId));
    }

    $this->visitRepository->save($visit);
  }
}