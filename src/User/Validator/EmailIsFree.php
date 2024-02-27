<?php

namespace App\User\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_CLASS)]
class EmailIsFree extends Constraint {

  public function __construct(
      public readonly bool $isCreate,
      mixed $options = null,
      ?array $groups = null,
      mixed $payload = null) {
    parent::__construct($options, $groups, $payload);
  }

  public function getMessage(): string {
    return 'email-is-already-taken';
  }

  public function getTargets(): string {
    return self::CLASS_CONSTRAINT;
  }

  public function validatedBy(): string {
    return static::class . ($this->isCreate ? 'Create' : 'Update') . 'Validator';
  }
}