<?php

namespace App\User\Validator;

use Attribute;
use Symfony\Component\Validator\Constraint;

#[Attribute(Attribute::TARGET_PROPERTY)]
class EmailIsFree extends Constraint {

  public function getMessage(): string {
    return 'email-is-already-taken';
  }

  public function getTargets(): string {
    return self::PROPERTY_CONSTRAINT;
  }
}