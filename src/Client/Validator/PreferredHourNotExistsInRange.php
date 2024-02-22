<?php

namespace App\Client\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class PreferredHourNotExistsInRange extends Constraint {

  public function __construct(
      public readonly bool $isCreate,
      mixed $options = null,
      ?array $groups = null,
      mixed $payload = null) {
    parent::__construct($options, $groups, $payload);
  }

  public function getMessage(): string {
    return 'preferred-hour-is-exists-in-range';
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }

  public function validatedBy(): string {
    return static::class . ($this->isCreate ? 'Create' : 'Update') . 'Validator';
  }
}