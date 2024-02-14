<?php

namespace App\Common\Utils;

use DateInterval;
use DateTimeImmutable;

class DateTimeImmutableUtils {

  public static string $dateFormat = 'Y-m-d';

  private static string $fullYearFormat = 'Y';
  private static string $monthNoFormat = 'm';
  private static string $weekNoFormat = 'W';

  public static function firstDayOfMonth(DateTimeImmutable $date): DateTimeImmutable {
    return (new DateTimeImmutable())
        ->setDate(self::year($date), self::month($date), 1)
        ->setTime(0, 0, 0);
  }

  public static function lastDayOfMonth(DateTimeImmutable $date): DateTimeImmutable {
    return self::firstDayOfMonth($date)
        ->add(new DateInterval('P1M'))
        ->sub(new DateInterval('P1D'));
  }

  public static function firstDayOfWeek(DateTimeImmutable $date): DateTimeImmutable {
    return (new DateTimeImmutable())
        ->setISODate(self::year($date), self::week($date))
        ->setTime(0, 0, 0);
  }

  public static function lastDayOfWeek(DateTimeImmutable $date): DateTimeImmutable {
    return self::firstDayOfWeek($date)->add(new DateInterval('P6D'));
  }

  public static function year(DateTimeImmutable $date): int {
    return intval($date->format(self::$fullYearFormat));
  }

  public static function month(DateTimeImmutable $date): int {
    return intval($date->format(self::$monthNoFormat));
  }

  public static function week(DateTimeImmutable $date): int {
    return intval($date->format(self::$weekNoFormat));
  }

  public static function addDay(DateTimeImmutable $date): DateTimeImmutable {
    return $date->add(new DateInterval('P1D'));
  }
}