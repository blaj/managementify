<?php

namespace App\Tests\Common\Doctrine\Type;

use App\Common\Doctrine\Type\BigIntType;
use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Types;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class BigIntTypeTest extends TestCase {

  private BigIntType $bigIntType;

  private AbstractPlatform $platform;

  public function setUp(): void {
    $this->bigIntType = new BigIntType();

    $this->platform = $this->createMock(AbstractPlatform::class);
  }

  /**
   * @test
   */
  public function givenValid_whenGetName_shouldReturnBigInt(): void {
    // given

    // when
    $name = $this->bigIntType->getName();

    // then
    Assert::assertEquals(Types::BIGINT, $name);
  }

  /**
   * @test
   */
  public function givenValid_whenGetSQLDeclaration_shouldReturnSqlDeclaration(): void {
    // given
    $column = [];

    $this->platform
        ->expects(static::once())
        ->method('getBigIntTypeDeclarationSQL')
        ->with($column)
        ->willReturn('declaration');

    // when
    $sqlDeclaration = $this->bigIntType->getSQLDeclaration($column, $this->platform);

    // then
    Assert::assertEquals('declaration', $sqlDeclaration);
  }

  /**
   * @test
   */
  public function givenValid_whenGetBindingType_shouldReturnString(): void {
    // given

    // when
    $bindingType = $this->bigIntType->getBindingType();

    // then
    Assert::assertEquals(ParameterType::STRING, $bindingType);
  }

  /**
   * @test
   */
  public function givenNonIntValue_whenConvertToPHPValue_shouldReturnNull(): void {
    // given
    $value = 'nonInt';

    // when
    $phpValue = $this->bigIntType->convertToPHPValue($value, $this->platform);

    // then
    Assert::assertNull($phpValue);
  }

  /**
   * @test
   */
  public function givenIntValue_whenConvertToPHPValue_shouldReturnInt(): void {
    // given
    $value = 123;

    // when
    $phpValue = $this->bigIntType->convertToPHPValue($value, $this->platform);

    // then
    Assert::assertNotNull($phpValue);
    Assert::assertEquals(intval($value), $phpValue);
  }
}