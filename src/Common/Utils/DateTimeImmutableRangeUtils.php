<?php

namespace App\Common\Utils;

use App\Common\Dto\DateTimeImmutableRange;
use DateTimeImmutable;

class DateTimeImmutableRangeUtils {

  public static function monthRange(DateTimeImmutable $date): DateTimeImmutableRange {
    return (new DateTimeImmutableRange())
        ->setFrom(DateTimeImmutableUtils::firstDayOfMonth($date))
        ->setTo(DateTimeImmutableUtils::lastDayOfMonth($date));
  }

  public static function weekRange(DateTimeImmutable $date): DateTimeImmutableRange {
    return (new DateTimeImmutableRange())
        ->setFrom(DateTimeImmutableUtils::firstDayOfWeek($date))
        ->setTo(DateTimeImmutableUtils::lastDayOfWeek($date));
  }

  /**
   * @template T
   * @param class-string<T> $className
   * @return array<T>
   */
  public static function mapDays(string $className, DateTimeImmutableRange $range, callable $callback): array {
    $resultArray = [];

    if ($range->getFrom() === null || $range->getTo() === null) {
      return [];
    }

    for ($startDate = $range->getFrom();
        $startDate <= $range->getTo();
        $startDate = DateTimeImmutableUtils::addDay($startDate)) {
      $resultArray[$startDate->format(DateTimeImmutableUtils::$dateFormat)] = $callback($startDate);
    }

    return $resultArray;
  }
}