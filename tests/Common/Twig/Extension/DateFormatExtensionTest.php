<?php

namespace App\Tests\Common\Twig\Extension;

use App\Common\Twig\Extension\DateFormatExtension;
use DateTimeImmutable;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class DateFormatExtensionTest extends TestCase {

  private DateFormatExtension $dateFormatExtension;

  public function setUp(): void {
    $this->dateFormatExtension = new DateFormatExtension();
  }

  /**
   * @test
   */
  public function givenNullDate_whenDateFormat_shouldReturnEmptyString(): void {
    // given
    $date = null;

    // when
    $formattedDate = $this->dateFormatExtension->dateFormat($date);

    // then
    Assert::assertEquals('', $formattedDate);
  }

  /**
   * @test
   */
  public function givenNotNullDate_whenDateFormat_shouldReturnDateString(): void {
    // given
    $date = new DateTimeImmutable('2023-01-01 10:00:00');

    // when
    $formattedDate = $this->dateFormatExtension->dateFormat($date);

    // then
    Assert::assertEquals('2023-01-01', $formattedDate);
  }
}