<?php

namespace App\Client\Service;

use App\Client\Dto\PreferredHourCreateRequest;
use App\Client\Dto\PreferredHourGroupDto;
use App\Client\Dto\PreferredHourRowDto;
use App\Client\Dto\PreferredHourUpdateRequest;
use App\Client\Entity\PreferredHour;
use App\Client\Mapper\PreferredHourUpdateRequestMapper;
use App\Client\Repository\PreferredHourRepository;
use App\Common\Entity\DayOfWeek;
use App\Company\Service\CompanyFetchService;
use Doctrine\ORM\EntityNotFoundException;

class PreferredHourService {

  public function __construct(
      private readonly PreferredHourRepository $preferredHourRepository,
      private readonly CompanyFetchService $companyFetchService,
      private readonly ClientFetchService $clientFetchService) {}

  /**
   * @return array<string, PreferredHourGroupDto>
   */
  public function getListGroup(int $clientId, int $companyId): array {
    $preferredHours =
        $this->preferredHourRepository->findAllByClientIdAndCompanyId($clientId, $companyId);

    $preferredHoursGroups = $this->getEmptyListGroup();

    foreach ($preferredHours as $preferredHour) {
      $groupKey = $preferredHour->getDayOfWeek()->value;

      if (!array_key_exists($groupKey, $preferredHoursGroups)) {
        $preferredHoursGroups[$groupKey] =
            (new PreferredHourGroupDto())->setDayOfWeek($preferredHour->getDayOfWeek());
      }

      $preferredHoursGroups[$groupKey]->addRow(
          (new PreferredHourRowDto())
              ->setId($preferredHour->getId())
              ->setFromTime($preferredHour->getFromTime())
              ->setToTime($preferredHour->getToTime()));
    }

    return $preferredHoursGroups;
  }

  public function create(
      int $clientId,
      PreferredHourCreateRequest $preferredHourCreateRequest,
      int $companyId): void {
    $preferredHours =
        array_map(
            fn (DayOfWeek $dayOfWeek) => (new PreferredHour())
                ->setDayOfWeek($dayOfWeek)
                ->setFromTime($preferredHourCreateRequest->getFromTime())
                ->setToTime($preferredHourCreateRequest->getToTime())
                ->setClient($this->clientFetchService->fetchClient($clientId, $companyId))
                ->setCompany($this->companyFetchService->fetchCompany($companyId)),
            $preferredHourCreateRequest->getDayOfWeeks());

    array_walk(
        $preferredHours,
        fn (PreferredHour $preferredHour) => $this->preferredHourRepository->save($preferredHour));
  }

  public function getUpdateRequest(int $id, int $companyId): ?PreferredHourUpdateRequest {
    return PreferredHourUpdateRequestMapper::map(
        $this->preferredHourRepository->findOneByIdAndCompany($id, $companyId));
  }

  public function update(
      int $id,
      PreferredHourUpdateRequest $preferredHourUpdateRequest,
      int $companyId): void {
    $preferredHour =
        $this->fetchPreferredHour($id, $companyId)
            ->setFromTime($preferredHourUpdateRequest->getFromTime())
            ->setToTime($preferredHourUpdateRequest->getToTime());

    $this->preferredHourRepository->save($preferredHour);
  }

  public function delete(int $id, int $companyId): void {
    $this->preferredHourRepository->softDeleteById(
        $this->fetchPreferredHour($id, $companyId)->getId());
  }

  /**
   * @return array<PreferredHourGroupDto>
   */
  private function getEmptyListGroup(): array {
    $preferredHoursGroups = [];

    foreach (DayOfWeek::cases() as $dayOfWeek) {
      $preferredHoursGroups[$dayOfWeek->value] =
          (new PreferredHourGroupDto())->setDayOfWeek($dayOfWeek);
    }

    return $preferredHoursGroups;
  }

  private function fetchPreferredHour(int $id, int $companyId): PreferredHour {
    return $this->preferredHourRepository->findOneByIdAndCompany($id, $companyId)
        ??
        throw new EntityNotFoundException('Preferred hour not found');
  }
}