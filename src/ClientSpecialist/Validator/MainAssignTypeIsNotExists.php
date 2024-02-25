<?php

namespace App\ClientSpecialist\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class MainAssignTypeIsNotExists extends Constraint {

  public function __construct(
      public readonly bool $isCreate,
      mixed $options = null,
      ?array $groups = null,
      mixed $payload = null) {
    parent::__construct($options, $groups, $payload);
  }

  public function getMessage(): string {
    return 'main-assign-type-is-exists';
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }

  public function validatedBy(): string {
    return static::class . ($this->isCreate ? 'Create' : 'Update') . 'Validator';
  }
}