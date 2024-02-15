<?php

namespace App\Visit\Service;

use App\Common\Dto\DateTimeImmutableRange;
use App\Common\Utils\DateTimeImmutableRangeUtils;
use App\Specialist\Repository\SpecialistRepository;
use App\Visit\Dto\CalendarDataColDto;
use App\Visit\Dto\CalendarDataVisitDto;
use App\Visit\Dto\CalendarDto;
use App\Visit\Dto\CalendarHeaderColDto;
use App\Visit\Dto\CalendarInfoColDto;
use App\Visit\Dto\CalendarRowDto;
use App\Visit\Dto\VisitFilterRequest;
use App\Visit\Entity\Visit;
use App\Visit\Repository\VisitRepository;
use DateTimeImmutable;

class VisitCalendarService {

  public function __construct(
      private readonly SpecialistRepository $specialistRepository,
      private readonly VisitRepository $visitRepository) {}

  public function getCalendar(VisitFilterRequest $visitFilterRequest, int $companyId): CalendarDto {
    $range = DateTimeImmutableRangeUtils::weekRange(new DateTimeImmutable());
    $specialists = $this->specialistRepository->findAllByCompanyId($companyId);
    $calendarRows = [];

    foreach ($specialists as $specialist) {
      $dataCols =
          DateTimeImmutableRangeUtils::mapDays(
              CalendarDataColDto::class,
              $range,
              function(DateTimeImmutable $date) use ($companyId, $specialist) {
                $visits =
                    $this->visitRepository->findAllBySpecialistIdAndOnDateAndCompanyId(
                        $specialist->getId(),
                        $date,
                        $companyId);

                return new CalendarDataColDto(
                    array_map(
                        fn (Visit $visit) => new CalendarDataVisitDto(
                            (new DateTimeImmutableRange())
                                ->setFrom($visit->getFromTime())
                                ->setTo($visit->getToTime())),
                        $visits));
              });

      $calendarRows[] =
          new CalendarRowDto(
              new CalendarInfoColDto($specialist->getFirstname(), $specialist->getSurname()),
              $dataCols);
    }

    return new CalendarDto($this->getHeaders($range), $calendarRows);
  }

  /**
   * @return array<CalendarHeaderColDto>
   */
  private function getHeaders(DateTimeImmutableRange $range): array {
    return DateTimeImmutableRangeUtils::mapDays(
        CalendarHeaderColDto::class,
        $range,
        fn (DateTimeImmutable $date) => new CalendarHeaderColDto($date));
  }
}