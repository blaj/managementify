<?php

namespace App\Report\Service\DataSource;

use App\Client\Entity\Client;
use App\Report\Dto\VisitSummary\DataSource\VisitSummaryDataSource;
use App\Report\Dto\VisitSummary\DataSource\VisitSummaryRow;
use App\Specialist\Entity\Specialist;
use App\Visit\Entity\Visit;
use App\Visit\Repository\VisitRepository;

class VisitSummaryReportDataSourceProviderService {

  public function __construct(private readonly VisitRepository $visitRepository) {}

  public function getDataSource(int $companyId): VisitSummaryDataSource {
    $visits = $this->visitRepository->findAllByCompanyId($companyId);

    $rows =
        array_map(
            fn (Visit $visit) => new VisitSummaryRow(
                self::specialistName($visit->getSpecialist()),
                self::clientName($visit->getClient()),
                $visit->getFromTime(),
                $visit->getToTime(),
                $visit->getNote(),
                $visit->getVisitType()?->getName(),
                $visit->getVisitType()?->getPreferredPrice()),
            $visits);

    return new VisitSummaryDataSource($rows);
  }

  private static function specialistName(Specialist $specialist): string {
    return $specialist->getSurname() . ' ' . $specialist->getFirstname();
  }

  private static function clientName(Client $client): string {
    return $client->getSurname() . ' ' . $client->getFirstname();
  }
}