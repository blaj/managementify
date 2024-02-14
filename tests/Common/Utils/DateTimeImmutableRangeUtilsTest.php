<?php

namespace App\Tests\Common\Utils;

use App\Common\Dto\DateTimeImmutableRange;
use App\Common\Utils\DateTimeImmutableRangeUtils;
use App\Common\Utils\DateTimeImmutableUtils;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class DateTimeImmutableRangeUtilsTest extends TestCase {

  /**
   * @test
   */
  public function givenDate_whenMonthRange_shouldReturnMonthRange(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $range = DateTimeImmutableRangeUtils::monthRange($date);

    // then
    Assert::assertEquals(
        (new DateTimeImmutableRange())
            ->setFrom(new DateTimeImmutable('2024-02-01 00:00:00'))
            ->setTo(new DateTimeImmutable('2024-02-29 00:00:00')),
        $range);
  }

  /**
   * @test
   */
  public function givenDate_whenWeekRange_shouldReturnWeekRange(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $range = DateTimeImmutableRangeUtils::weekRange($date);

    // then
    Assert::assertEquals(
        (new DateTimeImmutableRange())
            ->setFrom(new DateTimeImmutable('2024-02-12 00:00:00'))
            ->setTo(new DateTimeImmutable('2024-02-18 00:00:00')),
        $range);
  }

  /**
   * @test
   */
  public function givenCallable_whenMapDays_shouldReturnMappedArray(): void {
    // given
    $range = DateTimeImmutableRangeUtils::weekRange(new DateTimeImmutable('2024-02-15 12:00:00'));

    $callable = function(DateTimeImmutable $date) {
      return $date->format(DateTimeImmutableUtils::$dateFormat);
    };

    // when
    $mappedArray = DateTimeImmutableRangeUtils::mapDays('string', $range, $callable);

    // then
    Assert::assertEquals(
        [
            '2024-02-12' => '2024-02-12',
            '2024-02-13' => '2024-02-13',
            '2024-02-14' => '2024-02-14',
            '2024-02-15' => '2024-02-15',
            '2024-02-16' => '2024-02-16',
            '2024-02-17' => '2024-02-17',
            '2024-02-18' => '2024-02-18'],
        $mappedArray);
  }
}