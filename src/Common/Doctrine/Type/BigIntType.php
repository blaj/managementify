<?php

namespace App\Common\Doctrine\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\Types;

class BigIntType extends Type {

  public function getName(): string {
    return Types::BIGINT;
  }

  public function getSQLDeclaration(array $column, AbstractPlatform $platform): string {
    return $platform->getBigIntTypeDeclarationSQL($column);
  }

  public function getBindingType(): ParameterType {
    return ParameterType::STRING;
  }
  
  public function convertToPHPValue($value, AbstractPlatform $platform): ?int {
    return is_int($value) ? intval($value) : null;
  }
}