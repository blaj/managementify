<?php

namespace App\Tests\Common\Utils;

use App\Common\Utils\DateTimeImmutableUtils;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class DateTimeImmutableUtilsTest extends TestCase {

  /**
   * @test
   */
  public function givenDate_whenFirstDayOfMonth_shouldReturnFirstDayOfMonth(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $firstDayOfMonth = DateTimeImmutableUtils::firstDayOfMonth($date);

    // then
    Assert::assertEquals(new DateTimeImmutable('2024-02-01 00:00:00'), $firstDayOfMonth);
  }

  /**
   * @test
   */
  public function givenDate_whenLastDayOfMonth_shouldReturnLastDayOfMonth(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $lastDayOfMonth = DateTimeImmutableUtils::lastDayOfMonth($date);

    // then
    Assert::assertEquals(new DateTimeImmutable('2024-02-29 00:00:00'), $lastDayOfMonth);
  }

  /**
   * @test
   */
  public function givenDate_whenFirstDayOfWeek_shouldReturnFirstDayOfWeek(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $firstDayOfWeek = DateTimeImmutableUtils::firstDayOfWeek($date);

    // then
    Assert::assertEquals(new DateTimeImmutable('2024-02-12 00:00:00'), $firstDayOfWeek);
  }

  /**
   * @test
   */
  public function givenDate_whenLastDayOfWeek_shouldReturnFirstDayOfWeek(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $lastDayOfWeek = DateTimeImmutableUtils::lastDayOfWeek($date);

    // then
    Assert::assertEquals(new DateTimeImmutable('2024-02-18 00:00:00'), $lastDayOfWeek);
  }

  /**
   * @test
   */
  public function givenDate_whenYear_shouldReturnYear(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $year = DateTimeImmutableUtils::year($date);

    // then
    Assert::assertEquals(2024, $year);
  }

  /**
   * @test
   */
  public function givenDate_whenMonth_shouldReturnMonth(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $month = DateTimeImmutableUtils::month($date);

    // then
    Assert::assertEquals(2, $month);
  }

  /**
   * @test
   */
  public function givenDate_whenWeek_shouldReturnWeek(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $week = DateTimeImmutableUtils::week($date);

    // then
    Assert::assertEquals(7, $week);
  }

  /**
   * @test
   */
  public function givenDate_whenAddDay_shouldReturnAddedDay(): void {
    // given
    $date = new DateTimeImmutable('2024-02-15 12:00:00');

    // when
    $addedDay = DateTimeImmutableUtils::addDay($date);

    // then
    Assert::assertEquals(new DateTimeImmutable('2024-02-16 12:00:00'), $addedDay);
  }
}